<?php namespace BeEasy\BlogExtension;

use Event;
use Backend;
use System\Classes\PluginBase;
use RainLab\Blog\Models\Post;
use RainLab\Blog\Controllers\Posts as PostsController;

/**
 * BlogExtension Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Blog Extension',
            'description' => 'RainLab.Blog extension used by Be Easy Boards.',
            'author'      => 'Be Easy',
            'icon'        => 'icon-leaf',
        ];
    }

    /**
     * Extend the markdown parser.
     *
     * @return void
     */

    public function boot()
    {
        $this->extendPostsController();
        $this->extendMarkdown();
    }

    /**
     * Extend the PostsController
     *
     * @return void
     */
    protected function extendPostsController()
    {
        PostsController::extendFormFields(function($form, $model, $context) {
            if (!$model instanceof Post) return;
            $form->addSecondaryTabFields([
                'subtitle' => [
                    'tab' => 'Frontend',
                    'label' => 'Subtitle',
                    'default' => 'View post',
                ],
            ]);
        });
    }

    /**
     * Add custom markdown tags
     *
     * @return void
     */
    protected function extendMarkdown()
    {
        Event::listen('markdown.beforeParse', function($data) {

            // YouTube
            $data->text = preg_replace(
                '/\<(?:youtube)\s*=\s*\"?(\w+)\"?\s*\/?\>/im',
                '<div class="video-wrapper"><iframe type="text/html" src="http://www.youtube.com/embed/$1" frameborder="0"/></iframe></div>',
                $data->text
            );

            // Vimeo
            $data->text = preg_replace(
                '/\<(?:vimeo)\s*=\s*\"?(\w+)\"?\s*\/?\>/im',
                '<div class="video-wrapper"><iframe src="//player.vimeo.com/video/$1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>',
                $data->text
            );

            // Replace <left>, <center>, and <right> tags with <div>
            $data->text = preg_replace(
                '/\<(left|center|right)\>(.*)\<\/\1\>/im',
                '<div style="text-align: $1">$2</div>',
                $data->text
            );

            // Flexible row and column helpers
            $data->text = preg_replace('/\<(row|col|column)\>/i', '<div class="flex-$1">', $data->text);
            $data->text = preg_replace('/\<\/(row|col|column)\>/i', '</div>', $data->text);
        });
    }
}
