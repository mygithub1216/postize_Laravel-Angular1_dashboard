<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Extensions;
use App\Models\Post;
use App\Models\PostStatus;
use App\Models\PostTransformer;
use App\Models\UrlHelpers;
use Auth;
use DB;
use File;
use Html;
use Image;
use Log;
use JavaScript;
use Symfony\Component\HttpFoundation\Request;
use Session;


class ManagePostController extends Controller
{
    public function getAddEditPost($postId = null)
    {
        $post = [];
        if (!empty($postId)) {
            $post = Post::where('id', $postId)
                ->orderBy('id', 'desc')
                ->first();

            if (empty($post)) return redirect()->to('dashboard/post')->with('message', 'danger|The requested post does not exist.');
            $post->blocks = $post->blockcontent ? unserialize(base64_decode($post->blockcontent)) : [];

            JavaScript::put([
                "post" => $post,
                "blocks" => $post->blocks
            ]);
        } else {
            JavaScript::put([
                "post" => null,
                "blocks" => []
            ]);
        }

        return view('pages.admin.add-edit-post')
            ->with('post', $post)
            ->with('categories', Category::get());
    }

    public function postUploadImage(Request $request)
    {
        $this->validate($request, [
            'imagecontent' => 'image'
        ]);

        if ($request->hasFile('imagecontent') && $request->file('imagecontent')->isValid()) {

            Log::info('Payload included "image" parameter, and the image is valid');
            $filename = Extensions::getChars(32) . '.' . $request->file('imagecontent')->getClientOriginalExtension();
            $request->file('imagecontent')->move(
                public_path() . '/assets/front/img/', $filename
            );
            return response()->json(['success' => 'true', 'url' => url('assets/front/img/' . $filename)]);
        } else {
            return response()->json(['message' => 'Error'], 400);
        }
    }

    /**
     * @param Request $request
     * @param null $postId
     * @return mixed
     */
    public function postAddEditPost(Request $request, $postId = null)
    {
        if ($postId == null) {
            if (Post::where('slug', str_slug($request->input('title')))->exists()) {
                \Log::info('Slug existed:' . $request->input('title'));

                return redirect()->back()->withInput()->with('error', 'danger|This post has already been created, as it has the same URL as another post.');
            }

            Log::info('Creating new post...');
            $post = new Post();
            if (Auth::user()->type == 1)
                $post['status'] = $request->get('status');
            else
                $post['status'] = PostStatus::Pending;
            $post['user_id'] = Auth::user()->getAuthIdentifier();
        } else {
            $post = Post::find($postId);

            // TODO: Restore this once Ezra fixes articles
            /*if (Auth::user()->type == 0 && ($post->user_id != \Illuminate\Support\Facades\Auth::user()->getAuthIdentifier() || $post->status == PostStatus::Enabled)) {
                return redirect()->to('dashboard/post/list');
            }*/

            if ($request->input('status') == PostStatus::Deleted && $post['status'] != PostStatus::Deleted) {
                $post['deleted_at'] = Extensions::getDate();
            } else if ($request->input('status') != PostStatus::Deleted && $post['status'] == PostStatus::Deleted) {
                $post['deleted_at'] = null;
            }

            $post['status'] = $request->input('status');
        }

        if ($request->ajax() && $request->input('status') == PostStatus::Deleted) {
            $post['status'] = $request->input('status');
            $post['deleted_at'] = Extensions::getDate();
            $post->save();
            return response()->json(['success' => 'true']);
        }


        $post['title'] = $request->input('title');
        $post['slug'] = !empty($post['slug']) ? $post['slug'] : str_slug($post['title']);
        $post['internal_comments'] = $request->input('internal_comments');

        $post['description'] = $request->input('description');
        $post['category_id'] = $request->input('category_id', 1);
        $post->save();

        Log::info('Transforming content...');
        $postTransformer = new PostTransformer();
        $blocks = json_decode($request->input('blocks'));
        $content = [];

        for ($i = 0; $i < count($blocks); $i++) {

            if ($blocks[$i]->type == "text" || $blocks[$i]->type == "embed") {
                $newcontent = $blocks[$i]->content;
            } elseif ($blocks[$i]->type == "image") {
                $newcontent = "";

                if (!empty($blocks[$i]->title))
                    $newcontent .= "<h2>" . $blocks[$i]->title . "</h2>";

                if (!empty($blocks[$i]->description))
                    $newcontent .= "<p>" . $blocks[$i]->description . "</p>";

                $nc = '<img src="#" />';
                $transformed = $postTransformer->handleContentExternalUrls($blocks[$i]->url, $post->id);
                if ($transformed) {
                    $blocks[$i]->url = $transformed;
                    $nc = '<img src="' . $transformed . '" />';
                    $newcontent .= $nc;

                } else {
                    $newcontent .= $nc;
                }

                if (!empty($blocks[$i]->source) && !empty($blocks[$i]->sourceurl))
                    $newcontent .= "<span class='source'><span>via:</span> <a href='" . $blocks[$i]->sourceurl . "' target='_blank'>" . $blocks[$i]->source . "</a></span>";
                elseif (!empty($blocks[$i]->source) && empty($blocks[$i]->sourceurl))
                    $newcontent .= "<span class='source'><span>via:</span> " . $blocks[$i]->source . "</span>";
                elseif (empty($blocks[$i]->source) && !empty($blocks[$i]->sourceurl))
                    $newcontent .= "<span class='source'><span>via:</span> <a href='" . $blocks[$i]->sourceurl . "' target='_blank'>Source</a></span>";
            }

            array_push($content, $newcontent);
        }

        $post['content'] = base64_encode(serialize($content));
        $post['blockcontent'] = base64_encode(serialize($blocks));

        $post->save(); // Saving now to get an ID for naming the images

        /*
         * thumbnail processing
         */
        if ($request->get("thumbnail_output")) {
            $thumb = Image::make($request->get("thumbnail_output"));
            $filename = Extensions::getChars(6) . '_' . $post->id . '.jpg';
            $thumb->save(public_path() . '/' . config('custom.thumbs-directory') . $filename);

            $post['image'] = UrlHelpers::getThumbnailLink($filename);
        }

        $post->save();
        $message = 'success|Post saved successfully.';
        //$post->blocks = unserialize(base64_decode($post->content));
        return redirect()->to('dashboard/post/' . $post->id)
            ->with('post', $post)
            ->with('categories', Category::get())
            ->with('message', $message);
    }

    public function getPostList(Request $request)
    {
        $postStatesShown = [PostStatus::Enabled, PostStatus::RequiresRevision, PostStatus::ReadyForReview, PostStatus::Pending];

        $statusFilter = $request->has('statusFilter', Session::get('statusFilter', null));
        if($statusFilter) {
            Session::put('statusFilter', $statusFilter);

            switch($statusFilter) {
                case 0: $postStatesShown = [PostStatus::Pending]; break;
                case 1: $postStatesShown = [PostStatus::Enabled]; break;
                case 3: $postStatesShown = [PostStatus::ReadyForReview]; break;
                case 4: $postStatesShown = [PostStatus::RequiresRevision]; break;
            }
        }

        $postsPerPage = $request->get('postsPerPageFilter', Session::get('postsPerPageFilter', 20));
        Session::put('postsPerPageFilter', $postsPerPage);

        $posts = Post::join('user as u', 'u.id', '=', 'post.user_id')
            ->join('category as c', 'c.id', '=', 'post.category_id')
            ->whereIn('post.status', $postStatesShown)
            ->orderBy('id', 'desc')
            ->select(['post.*', 'u.name as author_name', 'u.email', 'u.image as author_image', 'c.name as category_name'])
            ->paginate($postsPerPage);

        $numberOfPostsRequiringRevision = 0;
        foreach($posts as $post) {
            if($post->status == PostStatus::RequiresRevision && Auth::user()->getAuthIdentifier() == $post->user_id)
                $numberOfPostsRequiringRevision++;
        }

        return view('pages.admin.post-list')
            ->with(['posts' => $posts, 'numberOfPostsRequiringRevision' => $numberOfPostsRequiringRevision]);
    }
}
