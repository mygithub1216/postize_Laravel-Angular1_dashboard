<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostService;
use App\Models\PostStatus;
use App\Models\UrlHelpers;
use Agent;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use View;
use DB;

class PostController extends Controller
{
    public function getPost(Request $request, $slug, $pageNumber = 1)
    {
        $preview = $request->get('__preview') == 1;
        $postService = new PostService();
        $post = $postService->getPostBySlug($slug, $preview);

        if (empty($post)) {
            \App::abort(404);
        }

        View::share('current_category', strtolower($post->category->name));

        $relatedPostsSidebar = Post::with('author')
            ->with('category')
            ->where('id', '!=', $post->id)
            ->where('status', PostStatus::Enabled)
            ->orderByRaw(DB::raw('RAND()'))
            ->take(12)
            ->get();

        $relatedPostsBottom = array_slice($relatedPostsSidebar->toArray(), 0, 3);

        $blockContent = unserialize(base64_decode($post->blockcontent));

        $numberOfPagesInArticle = 1;
        $pages = [];
        $currentPageContent = [];
        $utmSourceParameter = $request->get('utm_source', Session::get('source'));
        if($utmSourceParameter) {
            Session::put('source', $utmSourceParameter);
        }

        for ($i = 0; $i < count($blockContent); $i++) {
            if ($utmSourceParameter && $blockContent[$i]->type == 'pagebreak') {
                $pages[] = $currentPageContent;
                $currentPageContent = [];
            } else {
                if ($blockContent[$i]->type == 'image') {
                    $blockContent[$i]->content = '';

                    if (!empty($blockContent[$i]->title))
                        $blockContent[$i]->content .= '<h2>' . $blockContent[$i]->title . '</h2>';

                    if (!empty($blockContent[$i]->description))
                        $blockContent[$i]->content .= '<p>' . $blockContent[$i]->description . '</p>';

                    $blockContent[$i]->content .= '<img src="' . $blockContent[$i]->url . '" />';

                    if (!empty($blockContent[$i]->source) && !empty($blockContent[$i]->sourceurl)) {
                        $blockContent[$i]->content .= '<span class="source"><span>via:</span><a href="' .
                            $blockContent[$i]->sourceurl . '" target="blank">' . $blockContent[$i]->source . '</a></span>';
                    }
                }

                $currentPageContent[] = $blockContent[$i];
            }

            if ($i == count($blockContent) - 1) {
                $pages[] = $currentPageContent;
                $currentPageContent = [];
            }
        }

        if ($pageNumber == 0) $pageNumber = 1;
        if ($pageNumber > count($pages))
            return redirect()->to('/');

        $post->blocks = !$utmSourceParameter ? $pages[0] : $pages[$pageNumber - 1]; // Uses $pages[0] because without a utm_source parameter, the entire article is shown as 1 page.
        $post->is_last_page = count($pages) == $pageNumber;

        $nextPageUrl = url($slug) . '/' . ($pageNumber + 1);
        /*$urlParts = parse_url($request->fullUrl());
        if (!empty($urlParts['query'])) {
            $nextPageUrl .= '?' . $urlParts['query'];
        }*/

        return view('pages.post')
            ->with('post', $post)
            ->with('pageNumber', $pageNumber)
            ->with('nextPageUrl', $nextPageUrl)
            ->with('relatedPosts', $relatedPostsBottom)
            ->with('relatedPostsSidebar', $relatedPostsSidebar)
            ->with('preview', $preview)
            ->with('mobile', Agent::isMobile() || Agent::isTablet());
    }
}