<?php namespace BeEasy\BlogExtension\Classes;

use RainLab\Blog\Models\Post;

class PostsQuery {

    /**
     * The foundation of a posts query
     *
     * @return October\Rain\Database\Builder
     */
    public static function query($excludedSlug, $limit)
    {
        return Post::isPublished()
            ->where('slug', '<>', $excludedSlug)
            ->select('id', 'slug', 'title', 'subtitle')
            ->orderBy('published_at', 'desc')
            ->take($limit);
    }

    /**
     * Query related posts
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getRelated($slug, $limit)
    {
        return self::query($slug, $limit)
            ->whereHas('categories', function($category) use ($slug) {
                return $category->whereHas('posts', function($post) use ($slug) {
                    $post->whereSlug($slug);
                });
            })
            ->get();
    }

    /**
     * Query recent posts
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getRecent($slug, $related, $limit) {
        $excludedIds = $related->lists('id');

        return self::query($slug, $limit)
            ->whereNotIn('id', $excludedIds)
            ->get();
    }
}
