<?php
/**
 * DOMPDF - PHP5 HTML to PDF renderer
 *
 * File: $RCSfile: absolute_positioner.cls.php,v $
 * Created on: 2004-06-08
 *
 * Copyright (c) 2004 - Benj Carson <benjcarson@digitaljunkies.ca>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library in the file LICENSE.LGPL; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307 USA
 *
 * Alternatively, you may distribute this software under the terms of the
 * PHP License, version 3.0 or later.  A copy of this license should have
 * been distributed with this file in the file LICENSE.PHP .  If this is not
 * the case, you can obtain a copy at http://www.php.net/license/3_0.txt.
 *
 * The latest version of DOMPDF might be available at:
 * http://www.dompdf.com/
 *
 * @link http://www.dompdf.com/
 * @copyright 2004 Benj Carson
 * @author Benj Carson <benjcarson@digitaljunkies.ca>
 * @package dompdf

 */

/* $Id */

/**
 * Positions absolutly positioned frames
 */
class Absolute_Positioner extends Positioner {

  function __construct(Frame_Decorator $frame) { parent::__construct($frame); }

  function position() {

    $frame = $this->_frame;
    $style = $frame->get_style();
    
    $p = $frame->find_positionned_parent();
    
    list($x, $y, $w, $h) = $frame->get_containing_block();
    
    if ( $p ) {
      // Get the parent's padding box (see http://www.w3.org/TR/CSS21/visuren.html#propdef-top)
      list($x, $y) = $p->get_padding_box();
    }

    $top    = $style->length_in_pt($style->top,    $h);
    $right  = $style->length_in_pt($style->right,  $w);
    $bottom = $style->length_in_pt($style->bottom, $h);
    $left   = $style->length_in_pt($style->left,   $w);
    
    list($width, $height) = array($frame->get_margin_width(), $frame->get_margin_height());
    
    $orig_style = $this->_frame->get_original_style();
    $orig_width = $orig_style->width;
    $orig_height = $orig_style->height;
    
    if ( $left !== "auto" ) {
      $x += $left;
    }
    elseif ( $right !== "auto" && $orig_width === "auto" ) {
      $x += $w - $width - $right;
    }
    
    if ( $top !== "auto" ) {
      $y += $top;
    }
    elseif ( $bottom !== "auto" && $orig_height === "auto" ) {
      $y += $h - $height - $bottom;
    }

    $frame->set_position($x, $y);

  }
}