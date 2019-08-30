<?php
    /**
    * @name D3Ribbon
    * @version 1.0.1
    * @author Tufan Baris YILDIRIM
    * @link http://www.tufyta.com/php-ribbon
    *
    * The 'Ribbon User Interface' is a task-oriented Graphical User Interface (GUI).
    * It features a central menu button, widely known as the 'Office Button'.
    *
    * v1.0.1
    * ======
    * - Notice Errors resolved.
    * - $link changed yo $image on add_button method.
    *
    */
    class d3ribbon
    {
        /**
        * D3Ribbon Class and Styles Folder Name
        * @var string
        */
        public  $main_folder    = 'd3ribbon';
        /**
        * Style folder name
        *
        * @var mixed
        */
        public  $stlye          = 'blue';
        /**
        * Active tab for creating groups and buttons.
        *
        * @var mixed
        */
        public  $selected_category_id;
        /**
        * Active Button for set as selected
        *
        * @var mixed
        */
        public  $selected_button_id;
        /**
        * Category Objects
        *
        * @var mixed
        */
        private $categories     =array();
        /**
        * Group Objects
        *
        * @var mixed
        */
        private $groups         =array();
        /**
        * Button Objects
        *
        * @var mixed
        */
        private $buttons        =array();

           /**
           * Add Category (Tab)
           *
           * @param mixed $category_name
           * @param mixed $category_link
           * @param mixed $category_id
           *
           * @return mixed category id
           */
         public function add_category($category_name,$category_link, $category_id = null)
        {
            if($category_id === null)
                $category_id = count($this->categories)+1;

            $this->categories[$category_id]=array(
            'name' => $category_name,
            'link' => $category_link,
            'id' => $category_id
            );
            return (string)$category_id;
        }
        /**
        * Add Group to a Created Category
        *
        * @param mixed $category_id   owner category id
        * @param mixed $group_name    group display name
        * @param mixed $group_width   group width
        * @param mixed $group_id      group id
        * @return string group id
        */
        public function add_group($category_id,$group_name,$group_width = null,$group_id = null)
        {
            if($group_id === null)
                $group_id = count($this->groups) + 1;

            $this->groups[$category_id][$group_id] = array(
            'name'          => $group_name,
            'id'            => $group_id
            );

            $this->groups[$category_id][$group_id]['width'] = $group_width;

            return (string)$group_id;
        }

        /**
        * Add a button to created group
        *
        * @param mixed $group_id   owner group id
        * @param mixed $button_name button display name
        * @param mixed $link        button link
        * @param mixed $image       button image
        * @param mixed $button_id   button id
        */
        function add_button($group_id,$button_name,$link,$image,$button_id=null)
        {
            if($button_id === null)
                $button_id = count($this->buttons)+1;

            $this->buttons[$group_id][$button_id]=array(
            'name' => $button_name,
            'link' => $link,
            'image' => $image,
            'id'  => $button_id
            );

            return $button_id;
        }

        /**
        * Build the Ribbon.
        *
        * @param mixed $return
        */
        function build($return = true)
        {

            $ribbon='<table width="100%" cellspacing="0" cellpadding="0">
            <tr>
            <td id="cdnavcont" align="center">

            '.$this->build_ribbon_header($this->selected_category_id).'

            </td>
            </tr>
            <tr>
            <td id="cdribbon" valign="top" align="center">
            '.$this->build_ribbon_body($this->selected_category_id).'
            </td></tr>
            </table>  ';

            if ($return)
                return $ribbon;
            else
                echo $ribbon;
        }
        /**
        * Build Ribbon header for categories
        *
        * @param mixed $selected_category_id
        * @param mixed $return
        */
        private function build_ribbon_header($selected_category_id,$return = true)
        {
            $header = '<div id="cdnavheader">
            <ul>
            '.$this->build_ribbon_category_links($selected_category_id).'
            </ul>
            </div>';

            if ($return)
                return $header;
            else
                echo $header;

        }
        /**
        * Build Category Links.
        *
        * @param mixed $selected_id
        * @param mixed $return
        */
        private function build_ribbon_category_links($selected_id = '',$return = true)
        {
            $cat_links = '';
            foreach ($this->categories as  $cat)
            {
                $cat_links.=$this->build_ribbon_category_link($cat['id'],$selected_id == $cat['id']);
            }

            if ($return)
                return $cat_links;
            else
                echo $cat_links;
        }
        /**
        * Build single category
        *
        * @param mixed $category_id
        * @param mixed $selected
        * @param mixed $return
        */
        private function build_ribbon_category_link($category_id,$selected = false,$return = true)
        {
            $cat = &$this->categories[$category_id];
            $category_link = '<li id="'.($selected ? 'current' : 'cat_'.$category_id).'"><a href="'.$cat['link'].'"><span>'.$cat['name'].'</span></a></li> ';

            if($return)
                return $category_link;
            else
                echo $category_link;

        }
        /**
        * Build Ribbon body (Groups and buttons )
        *
        * @param mixed $category_id
        * @param mixed $return
        */
        private function build_ribbon_body($category_id,$return = true)
        {
            $rbody =  '<div id="ribbon_cd">
            <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr valign="top">
            <td width="2" class="cdribtopl">&nbsp;</td>
            <td width="946" class="cdribtopc"><div></div></td>
            <td width="2" class="cdribtopr"></td>
            </tr>
            <tr valign="middle" height="79">
            <td width="1" class="cdribmidl"><div></div></td>
            <td width="946" class="cdribmidc">
            <div style="padding-top:1px;" id="ribbon_burda">
            <table cellpadding="0" cellspacing="0" border="0" class="cdribbontext">
            <tr valign="top">
            <td width="2"> </td>

            '.$this->build_groups($category_id).'

            </tr>
            </table>
            </div>
            </td>
            <td width="1" class="cdribmidr"> <div></div></td>
            </tr>
            <tr valign="top">
            <td width="2" class="cdribbotl">&nbsp;</td>
            <td width="946" class="cdribbotc"></td>
            <td width="2" class="cdribbotr"></td>
            </tr>
            </table>

            </div>';

            if ($return)
                return $rbody;
            else
                echo $rbody;
        }
        /**
        * Build All  Groups with their buttons by  category_id
        *
        * @param mixed $category_id
        * @param mixed $return
        */
        function build_groups($category_id,$return = true)
        {
            $groups = "";
            if (isset($this->groups[$category_id]) && is_array($this->groups[$category_id]))
            {
                foreach ($this->groups[$category_id] as $group)
                {
                    $groups.=$this->build_group($category_id,$group['id']);
                }
            }

            if ($return)
                return $groups;
            else
                echo $groups;
        }
        /**
        * Build Single Group
        *
        * @param mixed $category_id
        * @param mixed $group_id
        * @param mixed $return
        */
        private function build_group($category_id,$group_id,$return = true)
        {

            $group = &$this->groups[$category_id][$group_id];

            return ' <td>
            <div class="cntRibbonborder">
            <table cellpadding="0" cellspacing="0"  border="0">
            <tr>
            <td width="2" class="cdchutopl"></td>
            <td class="cdchutopc"><div></div></td>
            <td width="2" class="cdchutopr"></td></tr>
            <tr valign="middle" height="74">
            <td width="1" class="cdchumidl"><div></div></td>
            <td '.(isset($group['width']) ?  'width="'.$group['width'].'"' : '').' class="cdchumidc" onmouseover="this.className=\'cdchumidcover\'" onmouseout="this.className=\'cdchumidc\'">

            <table height="85" cellspacing="0" width="100%">
            <tr><td class="buttonlar">

            <table height="100%">
            <tr>
            '.$this->build_buttons($group_id).'
            </tr>
            </table>

            </td></tr>
            <tr><td class="kategori_foot" align="center">'.$group['name'].'</td></tr>
            </table>
            </td>
            <td width="1" class="cdchumidr"><div></div></td>
            </tr>
            <tr valign="top"><td width="2" class="cdchubotl">
            </td><td class="cdchubotc"><div></div></td>
            <td width="2" class="cdchubotr"></td>
            </tr>
            </table>

            </div>
            </td>

            <td width="2"></td>  ';

        }
        /**
        * Build all buttons in a group
        *
        * @param mixed $group_id
        * @param mixed $return
        */
        private function build_buttons($group_id,$return = true)
        {
            $buttons = "";
            if (isset($this->buttons[$group_id]) && is_array($this->buttons[$group_id]))
            {
                foreach ($this->buttons[$group_id] as $btn)
                {
                    $buttons.=$this->build_button($group_id,$btn['id']);
                }
            }
            if ($return)
                return $buttons;
            else
                echo $buttons;
        }

        /**
        * build single button by id.
        *
        * @param mixed $group_id
        * @param mixed $button_id
        * @param mixed $return
        */
        private function build_button($group_id,$button_id,$return = true)
        {
            $button = &$this->buttons[$group_id][$button_id];

            if ($this->selected_button_id==$button_id)
            {
                $c1="button_over";
                $c2="button_over";
            }
            else
            {
                $c1="button";
                $c2="button_over";
            }

            $button_code = '<td align="center" class="'.$c1.'" onmouseover="this.className=\''.$c2.'\';" onmouseout="this.className=\''.$c1.'\';">
            <a href="'.$button['link'].'"><img border="0" src="'.$button['image'].'" width="32"><br>
            '.$button['name'].'   </a>
            </td>';
            if ($return)
                return $button_code;
            else
                echo $button_code;

        }
        /**
        *  get style folder path
        */
        public function stlyes_folder()        { return $this->main_folder.'/styles/';  }
        /**
        * get style file
        *
        */
        public function style_file()           { return $this->stlyes_folder().$this->stlye.'/d3ribbon.css';   }
        /**
        * get style file link
        *
        */
        public function style_link()           { return '<link href="'.$this->style_file().'" type="text/css" rel="stylesheet">';}

    }
?>