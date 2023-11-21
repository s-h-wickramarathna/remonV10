<?php
namespace App\Classes;


use Core\MenuManage\Models\Menu;
use Core\Permissions\Models\Permission;
use Illuminate\Support\Facades\Log;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class NestJob
{

    /**
     * Generate Dynamic Menu Function
     *
     * @param  Array $menu Arranged menu array
     */
    static function generate($menu)
    {


        $html = "";
        $i = 1;

        if (!empty($menu)) {
            $html .= '<ol class="dd-list">';
            foreach ($menu as $key => $element) {

                //Log::info($element);
                if (sizeof($element->children) == 0) {

                    //Log::info($element);

                    $html .= '<li class="dd-item" 
                          data-id="' . $element->id . '" 
                          data-label="' . $element->label . '"
                          data-ordering="' . $i . '">
                            <div class="dd-handle dd3-handle"></div>
                            <div class="dd3-content">
                               <i class="' . $element->id . '"></i>
                              ' . $element->name . '                              
                               <a href="' . url("job/data") . '/edit/' .$element->id. '" class="btn-link pull-right"><i class="fa fa-pencil"></i></a>
                               <span class="btn-link pull-right" style="margin-left:5px;margin-right:5px"> | </span>
                               <a href="#" class="btn-link pull-right menu-delete" data-id="' . $element->id . '" ><i class="fa fa-trash-o"></i></a>';

                    if($element->depth == 0){
                        $html .= '<span class="btn-link pull-right" style="margin-left:5px;margin-right:5px"> | </span>
                                <a href="' . url("job/data/add") . '/' . $element->id . '" class="btn-link pull-right">add '.strtolower($element->name).' data</a>';
                    }
                    $html .= '</div> </li>';

                } else {

                    // Log::info($element);
                    $html .= '<li class="dd-item"
                          data-id="' . $element->id . '" 
                          data-label="' . $element->label . '"
                          data-ordering="' . $i . '">';
                    $html .= '<div class="dd-handle dd3-handle"></div>';
                    $html .= '<div class="dd3-content">
                        
                        <i class="' . $element->id . '"></i>
                        ' . $element->name . '
                        
                        <a href="' . url("job/data")  . '/edit/' .$element->id. '" class="btn-link pull-right"><i class="fa fa-pencil"></i></a>
                               <span class="btn-link pull-right" style="margin-left:5px;margin-right:5px"> | </span>
                               <a href="#" class="btn-link pull-right menu-delete" data-id="' . $element->id . '" ><i class="fa fa-trash-o"></i></a>
                               <span class="btn-link pull-right" style="margin-left:5px;margin-right:5px"> | </span>
                                <a href="' . url("job/data/add") . '/' . $element->id . '" class="btn-link pull-right">add '.strtolower($element->name).' data</a>
                                       
                      </div>';

                    $html .= NestJob::generate($element->children);

                    $html .= "</li>";
                }
                $i++;
            }
            $html .= "</ol>";
        }

        return $html;
    }
}
