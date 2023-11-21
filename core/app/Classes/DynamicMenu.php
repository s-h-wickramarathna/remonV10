<?php
namespace App\Classes;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Core\MenuManage\Models\Menu;
use Core\Permissions\Models\Permission;

class DynamicMenu{
  
  /**
   * Generate Dynamic Menu Function
   *
   * @param  Integer $parent        Parent
   * @param  Array   $menu          Arranged menu array
   * @param  Integer $level         Menu Level
   * @param  Integer $currentUrlId  Id of Current Route Url
   * @param  Integer $userId        Logged in User Id
   * @return String                 Generated html string
   */
  static function generateMenu($parent, $menu, $level, $currentUrl,$userId){
    $user = Sentinel::findUserById($userId);
    // dd($user); ok
    $html = "";
    if(!empty($menu)){
      foreach ($menu as $key => $element) {
        $permissions = Permission::whereIn('name',json_decode($element->permissions))->where('status','=',1)->pluck('name');
        if($user->hasAnyAccess($permissions) && $element->status == 1){
          if(count($element->children) == 0){
            
            if($currentUrl && $currentUrl->id == $element->id){
              $html .= "<li class=\"active\">";
            }else{
              $html .= "<li>";
            }

            $html .= "<a href=\"".url($element->link)."\">";

            if($element->icon){
              $html .= "<i class=\"".$element->icon."\"></i>";
            }

            $html .= "<span>".$element->label."</span>";

            $html .= "</a></li>";
          }else{
            if($currentUrl && $element->isAncestorOf($currentUrl)){
              $html .= "<li class=\"menu-accordion open\">";
            }else{
              $html .= "<li class=\"menu-accordion\">";
            }

            $html .= "<a href=\"".url($element->link)."\">";

            if($element->icon){
              $html .= "<i class=\"".$element->icon."\"></i>";
            }else{
              if($currentUrl && $element->isAncestorOf($currentUrl)){
                $html .= "<i class=\"fa fa-folder-open\"></i>";
              }else{
                $html .= "<i class=\"fa fa-folder\"></i>";
              }
            }

            $html .= "<span>".$element->label."</span>";

            $html .= "</a>";

            $html .= "<ul class=\"sub-menu\">";
            $html .= DynamicMenu::generateMenu($element->id, $element->children, $element->getLevel(), $currentUrl, $userId);
            $html .= "</ul>";

            $html .= "</li>";
          }
        }
      }
    }
  	// dd($html);
  	// echo($html);
  	return $html;
  }
}
