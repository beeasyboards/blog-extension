<?php namespace BeEasy\BlogExtension\Controllers;

use RainLab\Blog\Models\Post;
use Illuminate\Routing\Controller;
use Owl\RainLabBlogApi\Models\Settings;
use BeEasy\BlogExtension\Classes\PostsQuery;

class PostsController extends Controller
{

    /**
     * Fetch most recent posts
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function recent()
    {
        $recent = PostsQuery::getHomeRecent(4);
        return PostsQuery::loadThumbnails($recent);
    }

    /**
     * Fetch related blog posts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function related($slug)
    {
        // Determine how many related posts to display
        $settings = Settings::get('beeasy', []);
        $limit = array_key_exists('related_limit', $settings)
            ? intval($settings['related_limit'])
            : 4;

        // Fetch the related posts based on common categories
        $related = PostsQuery::getRelated($slug, $limit);

        // If there aren't enough related posts, fill in the gaps with recent ones instead
        $relatedCount = $related->count();
        if ($relatedCount < $limit) {
            $recentLimit = $limit - $relatedCount;
            $recent = PostsQuery::getRelatedRecent($slug, $related, $recentLimit);
            $related = $related->merge($recent);
        }

        // Load the thumbnails and return the posts
        return PostsQuery::loadThumbnails($related);
    }
}
