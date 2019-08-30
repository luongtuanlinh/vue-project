<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SidebarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadSidebar();
    }

    public function loadSidebar()
    {
        $menus = config('menus');
        $sidebar = $this->renderSidebar($menus);

        view()->share('sidebar', $sidebar);
    }

    public function renderSidebar($menus)
    {
        $menus = collect($menus)->sortBy('order')->toArray();
        $path = '/'.app('request')->path();

        $menuSidebar = '';
        foreach ($menus as $menu){
            if ($path==$menu['router']) {
                $menu['active'] = true;
            } elseif ( isset($menu['sub']) && count($menu['sub'])>0 ) {
                foreach ($menu['sub'] as $sub) {
                    if ($sub['router']==$path) {
                        $menu['active'] = true;
                        break;
                    }
                }
            }
            $menuSidebar .= $this->getItem($menu);
        }

        return $menuSidebar;
    }

    public function getItem($item)
    {
        $submenu = '';
        if ( isset($item['sub']) && count($item['sub'])>0 ) {
            $submenu = $this->renderSidebar($item['sub']);
        }
        return view('sidebar.item-sidebar', compact('item', 'submenu'));
    }
}
