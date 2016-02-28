<?php namespace BeEasy\BlogExtension;

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
        // Extend the controller
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
}
