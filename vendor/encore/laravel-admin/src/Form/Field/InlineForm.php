<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

//use Encore\Admin\Form\Field\Select;
class InlineForm extends Field
{
    protected $class = 'btn-primary';
    public $prepend ="EGP";
    protected $groups;
    protected $options = [
        'alias'              => 'inlineForm', //sprint 3
        'radixPoint'         => '.',
        'prefix'             => '',
        'removeMaskOnSubmit' => true,
    ];
    public function info()
    {
        $this->class = 'btn-info';
        
        return $this;
    }

    public function on($event, $callback)
    {
        $this->script = <<<EOT

        $('{$this->getElementClassSelector()}').on('$event', function() {
            $callback
        });

EOT;
    }
    protected function initPlainInput()
    {
       
    }
    public function prepend(string $pre)
    {

    }
    public function addSelectItem($arr, $selected = null)
    {
        $this->groups = array();
        $uofmNames = array();
        $i = 0;
       if($selected == null){
           $selected = array();// sprint 3
       }
        $values = array();
        foreach($arr as $id => $name){
            $uofmNames[$i] = $name['name'];     
            $uofm = 0;
            $values = $arr[$id]->getUnits()->pluck('name','id');   
            if(sizeof($values) == 0)
               continue;
            $values->prepend('none', 0);
           
            foreach($values as $x => $key){
               $uofm = 0;
               foreach($selected as $vid=> $value){
                    if($x==$vid){
                      $uofm = $vid;
                      $this->groups[$i] = array('column'=> $uofm ,'options'=> $values, 'name'=>$name['code'], 'label' => trans('admin.listbox.'.$name['code']));
                      break;
                }     
               }
                if($uofm != 0)
            break;
            }
        
            if($uofm == 0)
           {
              $this->groups[$i] = array('column'=> $uofm ,'options'=> $values, 'name'=>$name['code'], 'label' => trans('admin.listbox.'.$name['code']));
           }
            $i++;
        }
     //   die;
      // @include('admin::form.select', {{'groups' => $groups,'name'=>'select'}})
       
    }
 
    public function render()
    {
        $this->label = trans('admin.unit');
        
     //   $groups = array(array('options' => array('five'=> 5,'six'=>6,'seven'=>7), 'label'=> trans('admin.area')));
        
        $this->variables['groups'] = $this->groups;
        //print_r( $this->variables());die;
        return parent::render();
    }
}