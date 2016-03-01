<?php namespace BeEasy\BlogExtension\Controllers;

use RainLab\Blog\Models\Post;
use Illuminate\Routing\Controller;
use Owl\RainLabBlogApi\Models\Settings;

class PostsController extends Controller
{

    /**
     * @var integer Default related posts limit
     */
    protected $limit = 5;

    /**
     * Fetch related blog posts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function related($slug)
    {
        // Fetch the related posts based on common categories
        $related = $this->fetchPosts($slug, $this->limit)
            ->whereHas('categories', function($category) use ($slug) {
                return $category->whereHas('posts', function($post) use ($slug) {
                    $post->whereSlug($slug);
                });
            })
            ->get();

        // If there aren't enough related posts, fill in the gaps with recent ones instead
        $relatedCount = $related->count();
        if ($relatedCount < $this->limit) {
            $related = $related->merge($this->fetchPosts($slug, $this->limit - $relatedCount)
                ->whereNotIn('id', $related->lists('id'))
                ->get());
        }

        // Load the thumbnail image
        $related->load(['featured_images' => function($images) {
            return $images->orderBy('sort_order', 'asc')->first();
        }]);

        return $related;
    }

    /**
     * Start building a query to fetch related posts
     *
     * @return Illuminate\Databse\Query\Builder
     */
    protected function fetchPosts($slug, $limit)
    {
        return Post::isPublished()
            ->where('slug', '<>', $slug)
            ->select('id', 'title', 'subtitle')
            ->orderBy('published_at', 'desc')
            ->take($limit);
    }
}
