<?php

/*
 * Provider for custom fields
 */

class PhFieldProvider extends GtcCodeProvider
{
    /**
     * identifier for the collapse
     * @var string
     */
    private $_collapseId = 'widget_collapse';

    public function generateColumn($model, $column)
    {
        switch ($column->name) {
            case 'created_at':
            case 'updated_at':
                return "#'{$column->name}'";
                break;
        }
    }

    public function generateActiveLabel($model, $column)
    {
        if ($column->autoIncrement) {
            return false;
        }
    }

    public function generateActiveField($model, $column, $view = NULL)
    {
        if ($column->autoIncrement) {
            return false;
        }
        if ($view == 'search') {
            return NULL;
        }
        switch ($column->name) {
            // disabled fields
            case 'copied_from_id':
            case 'created_at':
            case 'updated_at':
            case 'access_owner':
            case 'original_name':
            case 'path':
            case 'hash':
            case 'mime_type':
            case 'size':
            case 'p3_widget_id':
                return "echo \$form->textField(\$model,'{$column->name}',array('disabled'=>'disabled'))";
                break;
            // media select widget
            case 'default_p3media_id':
            case 'p3media_id':
                return "\$this->widget('P3MediaSelect', array('model'=>\$model,'attribute'=>'{$column->name}'))";
                break;
            case 'info_php_json':
            case 'info_image_magick_json':
                return "echo CVarDumper::dumpAsString(CJSON::decode(\$model->{$column->name}), 5, true)";
                break;
            case 'tree_parent_id':
                return "\$this->widget(
                    '\GtcRelation',
                    array(
                        'model' => \$model,
                        'relation' => 'treeParent',
                        'fields' => 'itemLabel',
                        'allowEmpty' => true,
                        'style' => 'dropdownlist',
                        'htmlOptions' => array(
                            'checkAll' => 'all'
                        ),
                        'criteria' => array(
                            'condition' => 'access_domain=:lang',
                            'params'    => array(
                                ':lang' => Yii::app()->language,
                            )
                        )
                    )
                )";
                break;
        }
        if (strstr($column->name, '_json')) {
            return "\$this->widget(
                'jsonEditorView.JuiJSONEditorInput',
                array(
                     'model'     => \$model,
                     'attribute' => '{$column->name}'
                )
            );";
        }
    }

    public function generateAttribute($modelClass, $column, $view = false)
    {
        #var_dump($modelClass, $column);exit;
        switch ($column->name) {
            // disabled fields
            case 'copied_from_id':
            case 'created_at':
            case 'updated_at':
            case 'access_owner':
            case 'access_read':
            case 'access_update':
            case 'access_delete':
            case 'access_append':
            case 'original_name':
            case 'path':
            case 'hash':
            case 'mime_type':
            case 'size':
                return "array(
                        'name' => '{$column->name}',
                        'type' => 'raw',
                        'value' => \$model->{$column->name}
                    ),\n";
                break;
            // media select widget
            case 'default_p3media_id':
            case 'p3media_id':
                #return "\$this->widget('P3MediaSelect', array('model'=>\$model,'attribute'=>'{$column->name}'))";
                break;
            case 'info_php_json':
            case 'info_image_magick_json':
                return "array(
                        'name' => 'Image',
                        'type' => 'raw',
                        'value' => CVarDumper::dumpAsString(CJSON::decode(\$model->{$column->name}), 5, true)
                    ),\n";

                break;

        }

        if ($column->isForeignKey) {
            return null;
        } elseif ($modelClass == 'vendor.phundament.p3Media.models.P3Media' && $column->name == 'id') {
            $code = "array(
                        'name'  => 'Image',
                        'type'  => 'raw',
                        'value' => \$model->image('p3media-manager')
                    ),\n";
        } elseif (strtoupper($column->dbType) == 'TEXT') {
            $code = "array(
                        'name'  => '{$column->name}',
                        'type'  => 'raw',
                        'value' => \$model->{$column->name}
                    ),\n";
        } else {
            $code = null;
        }
        return $code;
    }

    public function generateHtml($modelClass, $column, $view = false)
    {
        switch ($view) {
            case 'prepend':
                switch ($column->name) {
                    case 'tree_parent_id':
                        return "echo '<h3>Tree</h3>'";
                        break;
                    case 'default_page_title':
                        return "echo '<h3>A) Title, Layout & View</h3>'";
                        break;
                    case 'url_json':
                        return "echo '<h3>B) Weiterleitung</h3>'";
                        break;
                    case 'access_owner':
                        return $this->openCollapseGroup('collapseThree','Access');
                        break;
                    case 'status':
                        return $this->openCollapseGroup('collapseOne','Daten',true);
                        break;
                    case 'default_url_param':
                        return "echo '<h3>SEO</h3>'";
                        #return "echo '{$modelClass}{$column->name}{$view}'";
                        break;
                    case 'original_name':
                        return "echo '<h3>File</h3>'";
                        #return "echo '{$modelClass}{$column->name}{$view}'";
                        break;
                    case 'container_id':
                        return $this->openCollapseGroup('collapseTwo','Position');
                        #return "echo '{$modelClass}{$column->name}{$view}'";
                        break;
                }
                break;
            case 'append':
                switch ($column->name) {
                    case 'updated_at':
                    case 'session_param':
                    case 'name_id':
                    return $this->closeCollapseGroup();
                }
        }
    }

    /**
     * open the collpase row
     * @url http://getbootstrap.com/2.3.2/javascript.html#collapse
     * @param $id
     * @return string
     */
    private function openCollapseGroup($id,$title,$open = false){
        $openCssClass = '';
        if($open)
            $openCssClass = 'in';

        $code = <<<EOS
echo '
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#$this->_collapseId" href="#$id">
        $title
      </a>
    </div>
    <div id="$id" class="accordion-body collapse $openCssClass">
      <div class="accordion-inner">';
EOS;
        return $code;
    }

    /**
     * close the collapse row
     * @return string
     */
    private function closeCollapseGroup(){
        return "echo '</div></div></div>'";
    }
}
