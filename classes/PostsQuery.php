<?php namespace BeEasy\BlogExtension\Classes;

use RainLab\Blog\Models\Post;

class PostsQuery {

    /**
     * Start building a Posts query.
     *
     * @return October\Rain\Database\Builder
     */
    public static function query()
    {
        return Post::isPublished()
            ->select('id', 'slug', 'title', 'subtitle')
            ->orderBy('published_at', 'desc');
    }

    /**
     * Fetch related posts.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getRelated($slug, $limit)
    {
        return self::query()
            ->where('slug', '<>', $slug)
            ->whereHas('categories', function($category) use ($slug) {
                return $category->whereHas('posts', function($post) use ($slug) {
                    $post->whereSlug($slug);
                });
            })
            ->take($limit)
            ->get();
    }

    /**
     * Fetch recent posts for the home page.
     *
     * @param  integer
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getHomeRecent($limit)
    {
        return self::query()->take($limit)->get();
    }

    /**
     * Fetch recent posts when there aren't enough related posts.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getRelatedRecent($slug, $related, $limit) {
        $excludedIds = $related->lists('id');

        return self::query()
            ->where('slug', '<>', $slug)
            ->whereNotIn('id', $excludedIds)
            ->take($limit)
            ->get();
    }

    /**
     * Load the post thumbnails
     *
     * @param  Illuminate\Database\Eloquent\Collection
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function loadThumbnails($query)
    {
        $query->load([
            'featured_images' => function($image) {
                $image->select('attachment_id', 'disk_name', 'file_name', 'title', 'description');
            },
        ]);

        return $query;
    }
}
