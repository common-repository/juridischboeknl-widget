<?php
/*
Plugin Name: Juridischboek.nl widget
Plugin URI: http://www.Juridischboek.nl
Description: Voeg een widget toe met Juridischboeken
Author: Dennis Lutz
Version: 1.0.0
Author URI: http://www.Juridischboek.nl/
*/

  /**
   * echo widget
   * 
   * @return string
   */
  function jurwidget($options) {
    if( $options['num'] == "" ) $options['num'] = 10;
    $code = "
      <script src=\"http://static.managementboek.nl/widget/affwidget.js\" type=\"text/javascript\"></script>
      <script type=\"text/javascript\">
        //  mogelijke vars
        //  sort = auteur|7d|14d|30d|60d|90d|lm|titel
        //  desc = 0|1
        //  taal = nl|en
        //  rubriek = 'a|b'
        //  trefwoord = 'a,b'
        //  num = X
        //  q = 'zoekoptie'
        //  timer = tijd tussen automatisch
        var options = {
            affiliate:{$options['affiliate']},
            sort:'{$options['sort']}',
            num:{$options['num']},
            taal:'{$options['taal']}',
            desc:{$options['desc']},
            rubriek:'{$options['rubriek']}',
            trefwoord: '{$options['trefwoord']}',
            q:'{$options['q']}',
            timer:{$options['timer']},
            site:'jur'
        };
        initMgtBoekWidget(options);
      </script>
    ";
    echo $code;
  }

  /**
   * echo the widget
   */
  function widget_jurboek($args)  {
    extract($args);
    $options = get_option("widget_jurboek");
    //when there are no options set, use these values
    if (!is_array( $options )) {
      $options = array(
        'titel' => 'Juridischboek.nl Boeken',
        'affiliate'=>0,
        'sort'=>'',
        'num'=>10,
        'taal'=>'nl',
        'desc'=>0,
        'rubriek'=>'',
        'trefwoord'=>'',
        'q'=>'',
        'timer'=>5000,
        'site'=>'jur'
      );
    }
    echo $before_widget;
      echo $before_title . $options['titel'] . $after_title;
      echo jurwidget( $options );
    echo $after_widget;
  }
  /**
   * add widget and widgetcontrol to wordpress-admin
   */
  function jurboek_init() {
    register_sidebar_widget(__('Juridischboek boeken'), 'widget_jurboek');    
    register_widget_control(   'Juridischboek boeken', 'jurboek_control', 200, 200 );
  }
  
  /**
   * echo the widgetControl in wordpress-admin
   */
  function jurboek_control() {
    $options = get_option("widget_jurboek");
    if (!is_array( $options  )) {
      //no options found for widget_jurboek, use these defaults in the widgetform
      $options = array(
        'titel' => 'Juridischboek.nl boeken',
        'affiliate'=>0,
        'sort'=>'',
        'num'=>10,
        'taal'=>'nl',
        'desc'=>0,
        'rubriek'=>'',
        'trefwoord'=>'',
        'q'=>'',
        'timer'=>5000   
      );
    }
    //Set the variables if form is submitted
    if ($_POST['jurboekTitel-Submit']) {
      $options['titel']     = htmlspecialchars($_POST['jurboekTitel']);
      $options['affiliate'] = (integer)$_POST['jurboekAffiliate'];
      $options['sort']      = $_POST['jurboekSort'];
      $options['num']       = (integer)$_POST['jurboekNum'];
      $options['taal']      = $_POST['jurboekTaal'];
      $options['desc']      = $_POST['jurboekDesc'];
      $options['rubriek']   = $_POST['jurboekRubriek'];
      $options['trefwoord'] = $_POST['jurboekTrefwoord'];
      $options['q']         = $_POST['jurboekZoek'];
      $options['timer']     = $_POST['jurboekTimer'];
      update_option("widget_jurboek", $options);
    }
    //write the formfields
  ?>
    <input type="hidden" id="jurboekTitel-Submit" name="jurboekTitel-Submit" value="1" />
    <table>
    <tr><td><label for="jurboekTitel">Titel</label></td><td><input type="text" id="jurboekTitel" name="jurboekTitel" value="<?=$options['titel']?>" /></td></tr>
    <tr><td><label for="jurboekAffiliate">Affiliate</label></td><td><input type="text" id="jurboekAffiliate" name="jurboekAffiliate" value="<?=$options['affiliate']?>" /></td></tr>
    <!--
    <tr><td><label for="jurboekSort">Sort</label></td><td><input type="text" id="jurboekSort" name="jurboekSort" value="<?=$options['sort']?>" /></td></tr>
    -->
    <tr><td><label for="jurboekNum">Aantal</label></td><td><input type="text" id="jurboekNum" name="jurboekNum" value="<?=$options['num']?>" /></td></tr>
    <tr>
      <td><label for="jurboekTaal">Taal</label></td>
      <td>
        <select id="jurboekTaal" name="jurboekTaal">
         <option value="nl"<?php if($options['taal'] == "nl") echo " selected=\"selected\""; ?>>Nederlands</option>
         <option value="en"<?php if($options['taal'] == "en") echo " selected=\"selected\""; ?>>Engels</option>
       </select>
      </td>
    </tr>
    <tr>
      <td><label for="jurboekDesc">Volgorde</label></td>
      <td>
        <select id="jurboekDesc" name="jurboekDesc">
         <option value="0"<?php if($options['desc'] == "0") echo " selected=\"selected\""; ?>>Oplopend</option>
         <option value="1"<?php if($options['desc'] == "1") echo " selected=\"selected\""; ?>>Aflopend</option>
       </select>
      </td>
    </tr>
    <!--
    <tr>
      <td><label for="jurboekRubriek">Rubriek</label></td>
      <td>
        <select id="jurboekRubriek" name="jurboekRubriek">
         <option value=""<?php if($options['rubriek'] == "") echo " selected=\"selected\""; ?>>- alle rubrieken -</option>
       </select>
      </td>
    </tr>
    -->
    <tr><td><label for="jurboekTrefwoord">Trefwoord</label></td><td><input type="text" id="jurboekTrefwoord" name="jurboekTrefwoord" value="<?=$options['trefwoord']?>" /></td></tr>
    <tr><td><label for="jurboekZoek">Zoek</label></td><td><input type="text" id="jurboekZoek" name="jurboekZoek" value="<?=$options['q']?>" /></td></tr>
    <tr><td><label for="jurboekTimer">Refresh(ms)</label></td><td><input type="text" id="jurboekTimer" name="jurboekTimer" value="<?=$options['timer']?>" /></td></tr>  
  </table>
  <?php    
  };

  add_action("plugins_loaded", "jurboek_init");

?>