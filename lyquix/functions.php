<?php

defined('_JEXEC') or die('Restricted access');

class lyquixFlexicontentTmpl {

	private $jObject;
	private $tmplCustomObject;

	function __construct($passedJObject) {
		$this->jObject = $passedJObject;
	}

	function setTmplCustomObject($passedTmplCustomObject) {
		$this -> tmplCustomObject = $passedTmplCustomObject;
	}

	function renderCatButtons() {

		// Buttons
		
		$html = '';

		if (JRequest::getCmd('print')) {

			if ($this -> jObject -> params -> get('print_behaviour', 'auto') == 'auto') {

				$html .= '<script>jQuery(document).ready(function(){window.print();});</script>';

			}
			elseif ($this -> jObject -> params -> get('print_behaviour') == 'button') {

				$html .= '<input type="button" id="printBtn" name="printBtn" value="' . JText::_('Print') . '" class="btn btn-info" onclick="this.style.display=\'none\'; window.print(); return false;">';

			}

		}
		else {

			$_add_btn   = flexicontent_html::addbutton( $this -> jObject -> params, $this -> jObject -> category );
			$_print_btn = flexicontent_html::printbutton( $this -> jObject -> print_link, $this -> jObject -> params );
			$_mail_btn  = flexicontent_html::mailbutton( 'category', $this -> jObject -> params, $this -> jObject -> category -> slug );
			$_csv_btn   = flexicontent_html::csvbutton( 'category', $this -> jObject -> params, $this -> jObject -> category -> slug );
			$_feed_btn  = flexicontent_html::feedbutton( 'category', $this -> jObject -> params, $this -> jObject -> category -> slug );
			
			if ($_add_btn || $_print_btn || $_mail_btn || $_csv_btn || $_feed_btn) {

				if ($this -> jObject -> params -> get('btn_grp_dropdown')) {

					$html .= '<div class="buttons btn-group">'
								. '<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">'
									. '<span class="' . $this -> jObject -> params -> get('btn_grp_dropdown_class', 'icon-options') . '"></span>'
								. '</button>'
								. '<ul class="dropdown-menu" role="menu">'
									. ($_add_btn ? '<li>' . $_add_btn . '</li>' : '')
									. ($_print_btn ? '<li>' . $_print_btn . '</li>' : '')
									. ($_mail_btn ? '<li>' . $_mail_btn . '</li>' : '')
									. ($_csv_btn ? '<li>' . $_csv_btn . '</li>' : '')
									. ($_feed_btn ? '<li>' . $_feed_btn . '</li>' : '')
								. '</ul>'
							. '</div>';

				}
				else {
					
					$html .= '<div class="buttons">'
								. $_add_btn
								. $_print_btn
								. $_mail_btn
								. $_csv_btn
								. $_feed_btn
							. '</div>';

				}

			}

		}
		
		return $html;
		
	}


	function renderCatTitle() {

		// Title
		$html = '';

		if ($this-> jObject -> params -> get('show_cat_title', 1)) {

			// Use category title by default, or override if param not blank

			$cat_title = $this-> jObject -> params -> get('cat_title_override', htmlspecialchars($this-> jObject -> category -> title));

			// Get custom css class for title

			$cat_title_css = $this-> jObject -> params -> get('cat_title_css', '');

			// Limit max length of title of param > 0

			if ($this-> jObject -> params -> get('title_cut_text', 120) > 0) {
					
				$cat_title = substr($cat_title, 0, $this-> jObject -> params -> get('title_cut_text', 120));
				
			}

			$html .= '<h1' . ($cat_title_css ? '' : ' class="' . $cat_title_css . '"') . '>' . $cat_title . '</h1>';
			
		}
		
		return $html;
	}

	function renderCatAlpha() {

		// Alpha index
		
		$html = '';
		
		if ($this-> jObject -> params -> get('show_alpha', 1)) {
				
			$html .= '<div class="cat-filters ' . $this-> jObject -> params -> get('cat_alphaindex_class', '') . '">';
			$html .= $this-> jObject -> params -> get('cat_alphaindex_label', '');
			
			if($this-> jObject -> params -> get('cat_alphaindex_engine', 0)) {
				
				// Lyquix alphaindex engine
				$html .= 'Sorry, the Lyquix alphaindex engine has not been implemented yet.</div>';
				
			}
			else {
				
				echo $html;
				include (JPATH_SITE . DS . 'components' . DS . 'com_flexicontent' . DS . 'tmpl_common' . DS . 'category_alpha_html5.php');
				echo '</div>';
				$html = '';
				
			}

			echo '</div>';
		}
		
		return $html;
	}

	function renderCatImage() {

		// Category image
		
		$html = '';

		if ($this-> jObject -> params -> get('show_description_image', 1) && $this-> jObject -> params -> get('image')) {

			// get image from category parameters

			$src = $this-> jObject -> params -> get('image');

			// get resized image url
			$image_url = self::getCatImage($src, $this-> jObject -> params -> get('cat_image_width', 240), $this-> jObject -> params -> get('cat_image_height', 240), $this-> jObject -> params -> get('cat_image_method', 1));
			
			$html .= '<div class="cat-image ' . $this-> jObject -> params -> get('cat_img_align', '') . '"><img src="' . $image_url . '" /></div>';
		}
		
		return $html;
		
	}

	function renderCatDesc() {

		// Category description
		
		$html = '';

		if ($this-> jObject -> params -> get('show_description', 1) && $this-> jObject -> category -> description) {
				
			$html .= '<div class="cat-description">' . $this-> jObject -> category -> description . '</div>';
			
		}
		
		return $html;
		
	}

	function renderCatMap() {

		// Map
		
		$html = '';

		if ($this -> jObject -> params -> get('map_display', '') != '' && $this -> jObject -> params -> get('map_addr_field', '') != '') {
			
			$html .= '<div class="cat-map ' . $this -> jObject -> params -> get('map_css_class', '') . '">';	
			$html .= $this -> jObject -> params -> get('map_label', '');
			$html .= $this -> jObject -> params -> get('map_opentag', '');
			$html .= '<div id="cat-map" style="width:' . $this -> jObject -> params -> get('map_width', '100%') . '; height:' . $this -> jObject -> params -> get('map_height', '480px') . ';"></div>';
			$html .= $this -> jObject -> params -> get('map_closetag', '');
			$html .= '</div>';
			$html .= '<script src="//maps.googleapis.com/maps/api/js' . ($this -> jObject -> params -> get('map_google_api_key', '') ? '?key=' . $this -> jObject -> params -> get('map_google_api_key', '') : '') . '"></script>';
			$html .= '<script>
					var catMap = {
						options : {
							center: new google.maps.LatLng(0,0),
							mapTypeId: google.maps.MapTypeId.' . $this -> jObject -> params -> get('map_type', 'ROADMAP') . ',
							scrollwheel: ' . ($this -> jObject -> params -> get('map_zoom_scrollwheel', 0) ? 'true' : 'false') . ',
							mapTypeControl: ' . ($this -> jObject -> params -> get('map_type_control', 0) ? 'true' : 'false') . ',
							panControl: ' . ($this -> jObject -> params -> get('map_pan_control', 0) ? 'true' : 'false') . ',
							zoomControl: ' . ($this -> jObject -> params -> get('map_zoom_control', 1) ? 'true' : 'false') . ',
							streetViewControl: false,
							zoom: 8
						},
						bounds : new google.maps.LatLngBounds(),
						items : ' . self::renderCatMapItems() . ',
						infoWindows : {},
						markers : {},

						groupedItems: [],

					};

					jQuery(document).ready(function(){
						catMap.map = new google.maps.Map(document.getElementById(\'cat-map\'), catMap.options);
						google.maps.event.addListenerOnce(catMap.map, \'bounds_changed\', function(event){
							catMap.map.fitBounds(catMap.bounds);
							catMap.map.panToBounds(catMap.bounds);
						});

						// get catMap Item by Id, we want to get the original properties, such as id, html etc
						catMap.getItem = function(id) {
							// context, this = catMap.items
							var tResults = this.items.filter(function(item){
								return (item.id == id);
							});

							// filter returns array, but we only want the result to be a single item, so we return the first item in the array
							if (tResults.length == 1)
								return tResults[0];
							else 
								return {};
						};

						// get catMap group by id, most likely we want to count the number of items within the group, when we hide/show items on filter
						catMap.getGroup = function(id) {
							var tResults = this.groupedItems.filter(function(items){
								var found = false;
								items.forEach(function(item){
									if (item.id == id)
										found = true;
								});
								return found;
							})

							if (tResults.length == 1)
								return tResults[0];
							else 
								return {};
						};

						//group items to groupedItems as arrays. 
						for (var i = 0; i < catMap.items.length; i++) {
							// if empty, just push the item as array
							if (!catMap.groupedItems.length) {
								catMap.items[i].group = i;
								var newGroup = [catMap.items[i]]
								catMap.groupedItems.push(newGroup);
							} else {
								// if not empty, check the current item lat lon against all groups items
								var indexFound = -1;
								for(var j = 0; j < catMap.groupedItems.length; j++) {
									var found = false;
									for(var k = 0; k < catMap.groupedItems[j].length; k++) {
										// lat lon vicinity checker using the 4th decimal (0.0001000), meaning we check if the item is inside 7-11m radius
										var latPlus = catMap.groupedItems[j][k].lat + 0.0001000;
										var latMinus = catMap.groupedItems[j][k].lat - 0.0001000;

										var lonPlus = catMap.groupedItems[j][k].lon + 0.0001000;
										var lonMinus = catMap.groupedItems[j][k].lon - 0.0001000;
										if (latMinus < catMap.items[i].lat && catMap.items[i].lat < latPlus && lonMinus < catMap.items[i].lon && catMap.items[i].lon < lonPlus ) {
											found = true;
											break;
										}
									}

									if (found) {
										indexFound = j;
										break;
									}
								}

								// if the item is NOT inside the vicinity of other item in the groupedItems, push them in a new array group
								if (indexFound == -1) {
									catMap.items[i].group = catMap.groupedItems.length;
									var newGroup = [catMap.items[i]];
									catMap.groupedItems.push(newGroup);
								// if the item is inside the vicinity of other item in the groupedItems, push them in the same group array
								} else {
									catMap.items[i].group = indexFound;
									catMap.groupedItems[indexFound].push(catMap.items[i]);
								}
							}
						}

						for (var i = 0; i < catMap.items.length; i++) {
							if(catMap.items[i].lat && catMap.items[i].lon) {
								var itemLatLon = new google.maps.LatLng(catMap.items[i].lat, catMap.items[i].lon);
								catMap.bounds.extend(itemLatLon);
								var itemid = catMap.items[i].id;
								catMap.infoWindows[itemid] = new google.maps.InfoWindow({content: catMap.items[i].html});

								var labelString = \'\';
								var infoWindowHTML = catMap.items[i].html;
								if (catMap.groupedItems[catMap.items[i].group].length > 1) {
									labelString = catMap.groupedItems[catMap.items[i].group].length;
									infoWindowHTML = \'\';
									catMap.groupedItems[catMap.items[i].group].forEach(function(item){
										infoWindowHTML += item.html;
									})
								}
								var markerParams = {
									position: itemLatLon,
									map: catMap.map,
									title: catMap.items[i].title,
									html: infoWindowHTML,
									label: (labelString == \'\' ? \'\' : {text: labelString.toString(), color: "white"})
								}
								if(catMap.items[i].icon != \'\') markerParams.icon = catMap.items[i].icon;
								catMap.markers[itemid] = new google.maps.Marker(markerParams);
								google.maps.event.addListener(catMap.markers[itemid], \'click\', function() {
									catMap.infoWindows[itemid].setContent(this.html);
									catMap.infoWindows[itemid].open(catMap.map,this);
								});
							}
						}
						jQuery(window).on("screensizechange", function() {
							catMap.map.fitBounds(catMap.bounds);
							catMap.map.panToBounds(catMap.bounds);
						});
					});
					</script>';
				 
		}

		return $html;
		
	}

	function renderCatMapItems() {

		// json array
		$json = array();
		
		$field_name = $this-> jObject -> params -> get('map_addr_field', '');

		// generate json object of items
		foreach ($this-> jObject->items as $i => $item) {
			
			// include/exclude by content type
			if(!($this-> jObject -> params -> get('map_inc_exc_types', 0) xor in_array($this-> jObject -> items[$i] -> document_type, $this-> jObject -> params -> get('map_inc_exc_types_list', array())))) {
				
				// check if item has address field
				if (array_key_exists($field_name, $item -> fields)) {
	
					// check if field has lat/lon different than 0,0
	
					$addr = unserialize($item -> fields[$field_name] -> value[0]);
					
					if ((float)$addr['lat'] != 0 && (float)$addr['lon'] != 0) {
						
						$html = '';
						$html .= $this-> jObject -> params -> get('map_pretext', '');
						
						for ($j = 1; $j <= 7; $j++) {
							
							if (isset($item -> positions['group_' . $j])) {
								
								$html .= '<div class="group-' . $j . ' ' . $this-> jObject -> params -> get('css_group_' . $j, '') . '">';
								
								foreach ($item->positions['group_' . $j] as $field) {
									
									$html .= self::renderCatItemsField($item, $field, 'map');
								}
	
								$html .= '</div>';
							}
						}
						
						$html .= $this-> jObject -> params -> get('map_posttext', '');

                        $icon = method_exists($this -> tmplCustomObject,'customMapMarker') ? $this -> tmplCustomObject -> customMapMarker($item) : $this-> jObject -> params -> get('map_marker_icon', '');

						array_push($json, array('id' => $item -> id, 'title' => $item -> title, 'lat' => (float)$addr['lat'], 'lon' => (float)$addr['lon'], 'html' => $html, 'icon' => $icon));
						
					}
				}
			}
		}

		// print json array

		return json_encode($json);
	}

	function renderCatSubcats() {
		
		$html = '';
		
		// Subcategories
		// should display subcategories?

/*		if ($this-> jObject -> params -> get('map_display', '') != 'map' && $this-> jObject -> params -> get('show_subcategories', 0) && count($this-> jObject -> categories)) {
*/
		if ($this-> jObject -> params -> get('map_display', '') != 'map' && $this-> jObject -> params -> get('display_subcategories_items', 0) && count($this-> jObject -> categories)) {


			// BASED ON THE CATEGORY SORTING BUT FORCING IT until parameter filed is finihed

			$cat_sections = $this-> jObject -> params -> get('cat_layout_order', array("buttons", "title", "filters", "alpha", "image", "desc", "map", "subcats", "items", "pagination"));
			if (!is_array($cat_sections)) {
				$cat_sections = explode(",", $cat_sections);
			}
			
			$html .= '<div class="cat-subcats ' . $this-> jObject -> params -> get('sub_cat_class', '') . '">';
			
			// sub categories heading

			if ($this-> jObject -> params -> get('show_label_subcats', 1)) {
				
				$html .= $this-> jObject -> params -> get('sub_cat_label', '');
			}

			$html .= $this-> jObject -> params -> get('subcat_opentag', '');
			
			$html .= '<ul class="cat-subcats ' . $this-> jObject -> params -> get('sub_cat_ul_class', '') . '">';
			
			foreach ($this-> jObject->categories as $subcat) {
				
				$html .= '<li class="' 
					. $this-> jObject -> params -> get('sub_cat_li_class', '') 
					. (method_exists($this -> tmplCustomObject,'customSubcatClass') ? ' ' . $this -> tmplCustomObject -> customSubcatClass($subcat) : '')
					. '"'
					. (method_exists($this -> tmplCustomObject,'customSubcatAttrs') ? ' ' . $this -> tmplCustomObject -> customSubcatAttrs($subcat) : '') 
					. ">";
					
				$html .= $this-> jObject -> params -> get('subcat_pretext', '');
				
				// Subub-categories sections ordering
				$sub_cat_sections = $this-> jObject -> params -> get('sub_cat_layout_order', array("title", "image", "desc", "items", "teaser-image", "teaser-text"));
				
				if (!is_array($sub_cat_sections)) {
					$sub_cat_sections = explode(",", $sub_cat_sections);
				}

				$i = 1;
				
				foreach ($sub_cat_sections as $sub_cat_section) {
						
					switch ($sub_cat_section) {

						// Open Tag for Section
						
						case strstr($sub_cat_section, 'open'):
							$html .= '<div class="section-' . $i .'">';
							break;

						// Close Tag for Section

						case strstr($sub_cat_section, 'close'):
							$html .= '</div>';
							$i++;
							break;	

						// sub-category title

						case "title" :
							$html .= '<' . $this-> jObject -> params -> get('sub_cat_title_headding', 'h3') . '>';
							if ($this-> jObject -> params -> get('sub_cat_link_title', 1)) {
								$html .= '<a href="' . JRoute::_(FlexicontentHelperRoute::getCategoryRoute($subcat -> slug)) . '">';
							}

							$html .= htmlspecialchars($subcat -> title);

							if ($this-> jObject -> params -> get('sub_cat_link_title', 1)) {
								$html .= '</a>';
							}

							$html .= '</' . $this-> jObject -> params -> get('sub_cat_title_headding', 'h3') . '>';
							break;

						// sub-category image

						case "image" :
							
							if ($this-> jObject -> params -> get('show_description_image_subcat', 0) && $subcat -> params -> get('image')) {

								// get sub category image from its parameters

								$src = $subcat -> params -> get('image');

								// get resized image url
								$image_url = self::getCatImage($src, $this-> jObject -> params -> get('subcat_image_width', 240), $this-> jObject -> params -> get('subcat_image_height', 240), $this-> jObject -> params -> get('subcat_image_method', 1));

								$html .= '<div class="subcat-image">';

								// add link to sub category?

								if ($this-> jObject -> params -> get('subcat_link_image', 0)) {
									$html .= '<a href="' . JRoute::_(FlexicontentHelperRoute::getCategoryRoute($subcat -> slug)) . '">';
								}

								$html .= '<img src="' . $image_url . '" />';

								// close link tag

								if ($this-> jObject -> params -> get('subcat_link_image', 0)) {
									$html .= '</a>';
								}

								$html .= '</div>';
							}

							break;

						// sub-category description stripped of HTML and cut to given length

						case "desc" :
							if ($this-> jObject -> params -> get('show_description_subcat', 0) && $subcat -> description) {
								$html .= '<div class="subcat-description">' . flexicontent_html::striptagsandcut($subcat -> description, $this-> jObject -> params -> get('description_cut_text_subcat', 120)) . '</div>';
							}

							break;

						// items: displays a list of the sub-category items if they were generated, and not shown in the main items list

						case "items" :
							if ($this-> jObject -> params -> get('display_subcategories_items') && $this-> jObject -> params -> get('sub_cat_items', 0)) {
								
								if($this-> jObject -> params -> get('sub_cat_items_style', 'linkslist') == 'introitems') {
									
									$subcat_items = array();
									
									foreach ($this-> jObject->items as $i => $item) {
	
										// check that the item is in this subcategory
	
										foreach ($item->categories as $cat) {
											
											if ($cat -> id == $subcat -> id) {
												
												array_push($subcat_items, $i);
												
											}
											
										}
										
									}
									
									$html .= self::renderCatItemsSection($subcat_items, 'sub_cat_items_items', $subcat);
									
								} else {
								
								
									$html .= '<ul class="subcat-items">';
									
									foreach ($this-> jObject->items as $i => $item) {
	
										// check that the item is in this subcategory
	
										foreach ($item->categories as $cat) {
											
											if ($cat -> id == $subcat -> id) {
												
												$html .= '<li data-itemid="' . $item -> id . '">';
		
												// add link if type of items list if linklist
		
												if ($this-> jObject -> params -> get('sub_cat_items_style', 'linkslist') == 'linkslist') {
													$html .= '<a href="' . JRoute::_(FlexicontentHelperRoute::getItemRoute($item -> slug, $item -> categoryslug)) . '">';
												}
		
												$html .= htmlspecialchars($item -> title);
		
												// close link tag
		
												if ($this-> jObject -> params -> get('sub_cat_items_style', 'linkslist') == 'linkslist') {
													$html .= '</a>';
												}
												
												$html .= '</li>';
															
											}
										}
									}
	
									$html .= '</ul>';
									
								}
								
							}

							break;
						
						// Custom teaser image if the category image can't be used due to its proportions	
						
						case "teaser-image":
							
							if ($this-> jObject -> params -> get('show_description_image_subcat', 0) && $subcat -> params -> get('cat_teaser_img')) {

								// get sub category image from its parameters

								$src = $subcat -> params -> get('cat_teaser_img');

								// get resized image url
								$image_url = self::getCatImage($src, $this-> jObject -> params -> get('subcat_image_width', 240), $this-> jObject -> params -> get('subcat_image_height', 240), $this-> jObject -> params -> get('subcat_image_method', 1));

								$html .= '<div class="subcat-image">';

								// add link to sub category?

								if ($this-> jObject -> params -> get('subcat_link_image', 0)) {
									$html .= '<a href="' . JRoute::_(FlexicontentHelperRoute::getCategoryRoute($subcat -> slug)) . '">';
								}

								$html .= '<img src="' . $image_url . '" />';

								// close link tag

								if ($this-> jObject -> params -> get('subcat_link_image', 0)) {
									$html .= '</a>';
								}

								$html .= '</div>';
							}
							break;
						
						// Custom teaser text

						case "teaser-text":
							if ($this-> jObject -> params -> get('show_description_subcat', 0) && $subcat -> params ->get('cat_teaser_text',1)) {
								$html .= '<div class="subcat-description">' . flexicontent_html::striptagsandcut($subcat -> params ->get('cat_teaser_text'), $this-> jObject -> params -> get('description_cut_text_subcat', 120)) . '</div>';
							}
							break;	
					}
				}
				
				if($this-> jObject -> params -> get('sub_cat_viewall_link', 0)) {
					// insert a "view all" link
					$html .= '<a class="viewall" href="' . JRoute::_(FlexicontentHelperRoute::getCategoryRoute($subcat -> slug)) . '">' . 
						str_replace('{title}', $subcat -> title, $this-> jObject -> params -> get('sub_cat_viewall_label', 'View All {title} Items')) . 
						'</a>';
				}
				
				$html .= $this-> jObject -> params -> get('subcat_posttext', '');
				$html .= '</li>';
			}

			$html .= '</ul>';
			$html .= $this-> jObject -> params -> get('subcat_closetag', '');
			$html .= '</div>';
		}
		
		return $html;
		
	}

	function renderCatItems() {
			
		$html = '';
		
		if ($this-> jObject -> params -> get('map_display', '') != 'map') {
				
			$html .= '<div class="cat-items ' . $this-> jObject -> params -> get('items_css_class', '') . '">';
			$html .= $this-> jObject -> params -> get('items_label', '');
			
			if (count($this-> jObject -> items)) {
				
				// create arrays for items
				
				$featured_items = array();
				$leading_items = array();
				$intro_items = array();
				
				// display featured items in separate list?

				if ($this-> jObject -> params -> get('featured_separate', 0)) {

					foreach ($this-> jObject->items as $i => $item) {
						if ($item -> featured == 1) {

							// skip subcategory items if they are displayed under subcategories

							if ($this-> jObject -> params -> get('sub_cat_items', 0)) {

								// include only if the item is in the main category

								foreach ($item->categories as $cat) {
									if ($cat -> id == $this-> jObject -> category -> id) {
										array_push($featured_items, $i);
									}
								}
							}

							// else include all featured items

							else {
								array_push($featured_items, $i);
							}
						}
					}
				}

				// get number of leading items, leading items are shown only in first page

				if ($this-> jObject -> limitstart != 0) {
					$leading_num = 0;
				} else {
					$leading_num = $this-> jObject -> params -> get('leading_num', 0);
				}

				// display leading items

				if ($leading_num) {

					foreach ($this-> jObject->items as $i => $item) {

						// skip subcategory items if they are displayed under subcategories

						if ($this-> jObject -> params -> get('sub_cat_items', 0)) {

							// include only if the item is in the main category

							foreach ($item->categories as $cat) {
								if ($cat -> id == $this-> jObject -> category -> id) {

									// and skip any featured items displayed above

									if (!in_array($i, $featured_items)) {
										array_push($leading_items, $i);
									}
								}
							}
						}

						// else include all items except featured items displayed above

						else {
							if (!in_array($i, $featured_items)) {
								array_push($leading_items, $i);
							}
						}

						// break this foreach cycle once we have enough items

						if (count($leading_items) == $leading_num) {
							break;
						}
					}
				}


				// display intro items if there are any left after displaying featured and leading items

				if ((count($this-> jObject -> items) - count($featured_items) - count($leading_items)) > 0) {

					foreach ($this-> jObject->items as $i => $item) {

						// skip subcategory items if they are displayed under subcategories

						if ($this-> jObject -> params -> get('sub_cat_items', 0)) {

							// include only if the item is in the main category

							foreach ($item->categories as $cat) {
								if ($cat -> id == $this-> jObject -> category -> id) {

									// and skip any featured items and leading items displayed above

									if (!in_array($i, $featured_items) && !in_array($i, $leading_items)) {
										array_push($intro_items, $i);
									}
								}
							}
						}

						// else include all items except featured items and leading items displayed above

						else {
							if (!in_array($i, $featured_items) && !in_array($i, $leading_items)) {
								array_push($intro_items, $i);
							}
						}
					}
				}
				
				$items_list_layout_order = $this-> jObject -> params -> get('items_list_layout_order', array("featured", "leading","intro"));
				if (!is_array($items_list_layout_order)) {
					$items_list_layout_order = explode(",", $items_list_layout_order);
				}
				
				foreach ($items_list_layout_order as $list_section) {
						
					switch ($list_section) {

						case "intro" :
							$html .= self::renderCatItemsSection($intro_items, 'introitems');
							break;
						case "leading":
							$html .= self::renderCatItemsSection($leading_items, 'leading');
							break;
						case "featured":
							$html .= self::renderCatItemsSection($featured_items, 'featured');
							break;
					}
				}
			} else {

				// no items in this category
				if($this-> jObject -> params -> get('items_no_items', 0)) {
						
					$html .= '<span class="no-items">' . $this-> jObject -> params -> get('items_no_items_label', JText::_('FLEXI_NO_ITEMS_FOUND')) . '</span>';
					
				}
				
			}

			$html .= '</div>';
			
		}
		
		return $html;
	}

	function renderCatItemsSection($idx, $group, &$subcat = null) {
		
		$html = '';
		
		if (count($idx) > 0) {
			
			$html_json = $this-> jObject -> params -> get($group . '_html_json', 'html');
			
			// generate html
			if($html_json != 'json') {
				$html .= '<div class="' . $group . '-items ' . $this-> jObject -> params -> get($group . '_class', '') . '">';
				$html .= $this-> jObject -> params -> get($group . '_label', '');
				$html .= $this-> jObject -> params -> get($group . '_opentag', '');
				$html .= '<ul class="' . $group . '-items-list ' . $this-> jObject -> params -> get($group . '_ul_class', '') . '">';
				foreach ($idx as $i) {
					if(!($this-> jObject -> params -> get($group . '_inc_exc_types', 0) xor in_array($this-> jObject -> items[$i] -> document_type, explode(",", $this-> jObject -> params -> get($group . '_inc_exc_types_list', array()))))) {

						$html .= '<li class="' . 
								$this-> jObject -> params -> get($group . '_li_class', '') . 
								($this-> jObject -> items[$i] -> featured ? ' featured' : '') . ' ' .  
								(method_exists($this -> tmplCustomObject,'customItemClass') ? $this -> tmplCustomObject -> customItemClass($this-> jObject -> items[$i], $group) : '') .
								'" data-itemid="' . $this-> jObject -> items[$i] -> id . '"' .
								(method_exists($this -> tmplCustomObject,'customItemAttrs') ? $this -> tmplCustomObject -> customItemAttrs($this-> jObject -> items[$i], $group) : '') .
								'>';
						
						// wrap item in link
						if (($this-> jObject -> params -> get($group . '_link_item', 0))) {
							$item_link = JRoute::_(FlexicontentHelperRoute::getItemRoute($this-> jObject -> items[$i] -> slug, $this-> jObject -> items[$i] -> categoryslug));	
							$html .= '<a href="' . $item_link . '">';
						}
						
						$html .= $this-> jObject -> params -> get($group . '_pretext', '');
							
						for ($j = 1; $j <= 7; $j++) {
							
							if (isset($this-> jObject -> items[$i] -> positions['group_' . $j])) {
									
								$html .= '<div class="group-' . $j . ' ' . $this-> jObject -> params -> get('css_group_' . $j, '') . '">';
								
								foreach ($this-> jObject->items[$i]->positions['group_' . $j] as $field) {
									
									$html .= self::renderCatItemsField($this-> jObject -> items[$i], $field, $group);
									
								}
		
								$html .= '</div>';
							}
							
						}
						
						$html .= $this-> jObject -> params -> get($group . '_posttext', '');
						
						// close the link element
						if (($this-> jObject -> params -> get($group . '_link_item', 1))) {
								$html .= '</a>';
						}
						
						$html .= '</li>';
					}
					
				}
	
				$html .= '</ul>';
				$html .= $this-> jObject -> params -> get($group . '_closetag', '');
				$html .= '</div>';
			}
			
			// generate json
			if($html_json != 'html') {
				$json = array();
				foreach ($idx as $i) {
					
					$item_json = array();

			
					// include/exclude by content type
					if(!($this-> jObject -> params -> get($group . '_inc_exc_types', 0) xor in_array($this-> jObject -> items[$i] -> document_type, explode(",", $this-> jObject -> params -> get($group . '_inc_exc_types_list', array()))))) {
						
						if($this-> jObject -> params -> get($group . '_json_itemid', 1)) $item_json['id'] = $this-> jObject -> items[$i] -> id;
						if($this-> jObject -> params -> get($group . '_json_url', 1)) $item_json['url'] = JRoute::_(FlexicontentHelperRoute::getItemRoute($this-> jObject -> items[$i] -> slug, $this-> jObject -> items[$i] -> categoryslug));
						
						// get fields in the group 1-7 positions
						if($this-> jObject -> params -> get($group . '_json_group_fields', 1)) {
							for ($j = 1; $j <= 7; $j++) {
								
								if (isset($this-> jObject -> items[$i] -> positions['group_' . $j])) {
										
									foreach ($this-> jObject->items[$i]->positions['group_' . $j] as $field) {
										
										if($this-> jObject -> params -> get($group . '_json_field_id', 0)) $item_json[$field -> name]['id'] = $this-> jObject -> items[$i] -> fields[$field -> name] -> id;

										$item_json[$field -> name] = array();
										
										if($this-> jObject -> params -> get($group . '_json_field_value', 1)) {
											$item_json[$field -> name]['value'] = $this-> jObject -> items[$i] -> fields[$field -> name] -> iscore ? $this-> jObject -> items[$i] -> {$field -> name} : $this-> jObject -> items[$i] -> fieldvalues [$field -> id];
											// process serialized data
											if(is_array($item_json[$field -> name]['value'])) {
												foreach($item_json[$field -> name]['value'] as $value_idx => $value) {
													$value = @unserialize($value);
													if($value) $item_json[$field -> name]['value'][$value_idx] = $value;
												}
											}
											else {
												$value = @unserialize($item_json[$field -> name]['value']);
												if($value) $item_json[$field -> name]['value'] = $value;
											}
										}

										if($this-> jObject -> params -> get($group . '_json_field_display', 1)) $item_json[$field -> name]['display'] = self::renderCatItemsField($this-> jObject -> items[$i], $field, $group);
										
									}
								
								}
								
							}
						}
						
						// get fields in the renderonly position
						if ($this-> jObject -> params -> get($group . '_json_renderonly_fields', 1) && isset($this-> jObject -> items[$i] -> positions['renderonly'])) {
								
							foreach ($this-> jObject->items[$i]->positions['renderonly'] as $field) {
								
								if($this-> jObject -> params -> get($group . '_json_field_id', 0)) $item_json[$field -> name]['id'] = $this-> jObject -> items[$i] -> fields[$field -> name] -> id;

								$item_json[$field -> name] = array();
								
								if($this-> jObject -> params -> get($group . '_json_field_value', 1)) {
									$item_json[$field -> name]['value'] = $this-> jObject -> items[$i] -> fields[$field -> name] -> iscore ? $this-> jObject -> items[$i] -> {$field -> name} : $this-> jObject -> items[$i] -> fieldvalues [$field -> id];
									// process serialized data
									if(is_array($item_json[$field -> name]['value'])) {
										foreach($item_json[$field -> name]['value'] as $value_idx => $value) {
											$value = @unserialize($value);
											if($value) $item_json[$field -> name]['value'][$value_idx] = $value;
										}
									}
									else {
										$value = @unserialize($item_json[$field -> name]['value']);
										if($value) $item_json[$field -> name]['value'] = $value;
									}
								}

								if($this-> jObject -> params -> get($group . '_json_field_display', 1)) $item_json[$field -> name]['display'] = self::renderCatItemsField($this-> jObject -> items[$i], $field, $group);
								
							}
						
						}
						
					}

					if(count($item_json)) array_push($json, $item_json); 
					
				}

				$subcat = $subcat ? preg_replace("/[^A-Za-z0-9]/", '', $subcat -> title) : '';

				$html .= '<script>var ' . ($group == 'sub_cat_items_items' ? 'subcat' . $subcat : $group) . 'Items = ' . json_encode($json) . ';</script>';

				// detect silent error
				if (json_last_error_msg() != 'No error')
					echo '<script>console.log("WARNING json_decode failed silently on /components/com_flexicontent/templates/lyquix/functions.php, line 872: " + "' . json_last_error_msg() .'")</script>';				
				
			}
			
		}
		
		return $html;
	}

	function renderCatItemsField(&$item, &$field, $group) {
		
		$css_fields = (object) json_decode($this-> jObject -> params -> get('item_css_fields', '{}'));
		
		$html = '';
		
		// link to item

		$item_link = JRoute::_(FlexicontentHelperRoute::getItemRoute($item -> slug, $item -> categoryslug));
		
		// try custom rendering first
		$html .= method_exists($this -> tmplCustomObject,'customFieldRendering') ? $this -> tmplCustomObject -> customFieldRendering($item, $field, $group) : '';
		
		if(!$html) {
			
			// field pretext
			$html .= method_exists($this -> tmplCustomObject,'customFieldRenderingPretext') ? $this -> tmplCustomObject -> customFieldRenderingPretext($item, $field, $group) : '';

			switch ($field->name) {
	
				// if title or the title override field
	
				case "title" :
				case $this-> jObject->params->get($group . '_title_field', '') :
	
					// format title
	
					if (($field -> name == 'title' && !$this-> jObject -> params -> get($group . '_title_field', 0)) || $field -> name == $this-> jObject -> params -> get($group . '_title_field', 0)) {
	
						// show item title?
	
						if ($this-> jObject -> params -> get('show_title', 1)) {
							$html .= '<' . $this-> jObject -> params -> get($group . '_title_headding', 'h3');
							$html .= (property_exists($css_fields, $field -> name) ? ' class="' . $css_fields -> {$field -> name} . '"' : '') . '>';
	
							// make title clickable?
	
							if ($this-> jObject -> params -> get('link_titles', 0)) {
								$html .= '<a href="' . $item_link . '">';
							}
	
							$html .= htmlspecialchars($item -> fields[$field -> name] -> value[0]);
	
							// make title clickable?
	
							if ($this-> jObject -> params -> get('link_titles', 0)) {
								$html .= '</a>';
							}
	
							$html .= '</' . $this-> jObject -> params -> get($group . '_title_headding', 'h3') . '>';
						}
					} else {
						$html .= '<div class="field field_' . $field -> name . '">';
						if ($field -> label) {
							$html .= '<div class="label">' . $field -> label . '</div>';
						}
	
						$html .= $field -> display . '</div>';
					}
	
					break;
	
				// set date format for reated and modified dates
	
				case "created" :
				case "modified" :
					$html .= '<div class="date ' . $field -> name . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					$html .= JHTML::_('date', $item -> fields[$field -> name] -> value[0], $this-> jObject -> params -> get($group . '_date_format', "l, F jS, Y")) . '</div>';
					break;
	
				// designated image field
	
				case $this-> jObject->params->get($group . '_img', '') :
	
					$image = self::getItemImage($item, $this-> jObject->params->get($group . '_img', ''), $this-> jObject -> params -> get($group . '_img_size', 'l'), $this-> jObject -> params -> get($group . '_img_width', 160), $this-> jObject -> params -> get($group . '_img_height', 90), $this-> jObject -> params -> get($group . '_img_method', '0'));

					/*
					// get image source, use selected size or get large
	
					$img_size_map = array('l' => 'large', 'm' => 'medium', 's' => 'small');
					$img_field_size = $img_size_map[$this-> jObject -> params -> get($group . '_img_size', 'l')];
					$src = str_replace(JURI::root(), '', $item -> fields[$field -> name] -> thumbs_src[$img_field_size][0]);
	
					// if custom size generate url with phpthumb
	
					if (!$this-> jObject -> params -> get($group . '_img_size')) {
						$w = '&amp;w=' . $this-> jObject -> params -> get($group . '_img_width', 160);
						$h = '&amp;h=' . $this-> jObject -> params -> get($group . '_img_height', 90);
						$aoe = '&amp;aoe=1';
						$q = '&amp;q=95';
						$zc = $this-> jObject -> params -> get($group . '_img_method', '0') ? '&amp;zc=' . $this-> jObject -> params -> get($group . '_img_method', '0') : '';
						$ext = pathinfo($src, PATHINFO_EXTENSION);
						$f = in_array($ext, array('png', 'ico', 'gif')) ? '&amp;f=' . $ext : '';
						$conf = $w . $h . $aoe . $q . $zc . $f;
						$base_url = (!preg_match("#^http|^https|^ftp#i", $src)) ? JURI::base(true) . '/' : '';
						$img_url = JURI::base(true) . '/components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . $base_url . $src . $conf;
					} else {
						$img_url = $src;
					}
					*/

					// set wrapping div
	
					$group_img_class = $this-> jObject -> params -> get($group . '_img_class', '');
					$group_img_align = $this-> jObject -> params -> get($group . '_img_align', '');
					$html .= '<div class="image field_' . $field -> name 
						. ($group_img_align ? ' ' . $group_img_align : '') . ($group_img_class ? ' ' . $group_img_class : '')
						. (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '')
						. '">';
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					// image clickable?
	
					if ($this-> jObject -> params -> get($group . '_img_link', '1')) {
						$html .= '<a href="' . $item_link . '">';
					}
	
					$html .= '<img src="' . $image['url'] . '" alt="' . htmlspecialchars($item -> title) . '" />';
	
					// image clickable?
	
					if ($this-> jObject -> params -> get($group . '_img_link', '1')) {
						$html .= '</a>';
					}
	
					$html .= '</div>';
					break;
	
				// item description field or override field
	
				case "text" :
				case $this-> jObject->params->get($group . '_desc_field', '') :
					if (($field -> name == 'text' && !$this-> jObject -> params -> get($group . '_desc_field', '')) || $field -> name == $this-> jObject -> params -> get($group . '_desc_field', '')) {
						if ($field -> name == 'text') {
	
							// get item description, and strip & cut html
	
							$item -> fields[$field -> name] -> value[0] = FlexicontentFields::getFieldDisplay($item, $field -> name, $values = null, $method = 'display');
							$text = substr(flexicontent_html::striptagsandcut($item -> fields[$field -> name] -> value[0], $this-> jObject -> params -> get($group . '_desc_cut', 200)), 0, $this-> jObject -> params -> get($group . '_desc_cut', 200));
						} else {
	
							// get plain text and cut to max length
	
							$text = htmlspecialchars($item -> fields[$field -> name] -> value[0]);
							if ($this-> jObject -> params -> get($group . '_desc_cut', 200) >= 0 && $this-> jObject -> params -> get($group . '_desc_cut', 200) < strlen($text)) {
								$text = substr($text, 0, $this-> jObject -> params -> get($group . '_desc_cut', 200));
							}
						}
	
						$html .= '<div class="description' . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
	
						// add label?
	
						if ($field -> label) {
							$html .= '<div class="label">' . $field -> label . '</div>';
						}
	
						$html .= '<p>' . $text . '</p>';
	
						$html .= '</div>';
						
					} else {
							
						$html .= '<div class="field field_' . $field -> name . '">';
						
						if ($field -> label) {
							$html .= '<div class="label">' . $field -> label . '</div>';
						}
	
						$html .= $field -> display . '</div>';
					}
	
					break;
				
				case 'created_by':
					
					$html .= '<div class="author ' . $field -> name . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					$html .= $this-> jObject -> params -> get($group . '_writtenby_label', '') . $field -> display . '</div>';
					
					break;
					
				// display any other field
	
				default :
					$html .= '<div class="field field_' . $field -> name . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
					
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					$html .= $field -> display . '</div>';
					break;
			}

			$html = iconv("UTF-8", "ASCII//TRANSLIT", $html);

			// field posttext
			$html .= method_exists($this -> tmplCustomObject,'customFieldRenderingPosttext') ? $this -> tmplCustomObject -> customFieldRenderingPosttext($item, $field, $group) : '';
			
		}

		// add readmore link?
		if ($this-> jObject -> params -> get('show_readmore') && $this-> jObject -> params -> get($group . '_readmore_after', 'text') == $field -> name) {
			$readmore = $this-> jObject -> params -> get($group . '_readmore_label', 'Read More');
			$readmore = str_replace('{title}', $item -> title, $readmore);
			$html .= '<a class="readmore" href="' . $item_link . '">' . $readmore . '</a>';
		}

		// add addthis toolbar for this item?
		// to do: we need to add some parameters that indicate the configuration of the addthis bar
		if ($this-> jObject -> params -> get('items_addthis', 0) && $this-> jObject -> params -> get('items_addthis_after', '') == $field -> name) {
			$html .= '<div class="addthis_toolbox addthis_default_style " addthis:url="' . JURI::root() . substr($item_link, 1) . '">' . 
				$this-> jObject -> params -> get('items_addthis_services','<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a><a class="addthis_button_tweet"></a><a class="addthis_counter addthis_pill_style"></a>') .  
				'</div>';
		}

		// add disqus link?
		if ($this-> jObject -> params -> get('items_disqus', 0) && $this-> jObject -> params -> get('items_disqus_after', '') == $field -> name) {
			$html .= '<div class="disqus_comments"><a href="' . JURI::root() . substr($item_link, 1) . '#disqus_thread">Comments</a></div>';
		}

		return $html;
	}

	function renderCatPagination() {

		// Pagination
		$html = '';
		
		if ($this-> jObject -> params -> get('show_pagination', 0)) {
			
			$html .= '<div class="pagination ' . $this-> jObject -> params -> get('pagination_css_class', '') . '">';
			$html .= $this-> jObject -> params -> get('pagination_label', '');
			$html .= '<div class="pageslinks">' . $this-> jObject -> pageNav -> getPagesLinks() . '</div>';
			
			if ($this-> jObject -> params -> get('show_pagination_results', 1)) {
				$html .= '<div class="pagescounter">' . $this-> jObject -> pageNav -> getPagesCounter() . '</div>';
			}

			$html .= '</div>';
		}
		
		return $html;
		
	}

	function renderItemField(&$item, &$field) {
		$css_fields = (object) json_decode($this-> jObject -> params -> get('item_css_fields', '{}'));
		
		$html = '';
		
		// try custom rendering first
		$html .= method_exists($this -> tmplCustomObject,'customFieldRendering') ? $this -> tmplCustomObject -> customFieldRendering($item, $field) : '';
		if(!$html) {
			
			// field pretext
			$html .= method_exists($this -> tmplCustomObject,'customFieldRenderingPretext') ? $this -> tmplCustomObject -> customFieldRenderingPretext($item, $field, $group) : '';

			switch ($field->name) {
	
				// if title or the title override field
	
				case "title" :
	
					// show item title?
	
					if ($this-> jObject -> params -> get('show_title', 1)) {
						$html .= '<h1' . (property_exists($css_fields, $field -> name) ? ' class="' . $css_fields -> {$field -> name} . '"' : '') . '>' . htmlspecialchars($item -> fields[$field -> name] -> value[0]) . '</h1>';
					}
	
					break;
	
				// set date format for reated and modified dates
	
				case "created" :
				case "modified" :
					$html .= '<div class="date ' . $field -> name . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					$html .= JHTML::_('date', $item -> fields[$field -> name] -> value[0], $this-> jObject -> params -> get('item_date_format', "l, F jS, Y")) . '</div>';
					break;
	
				// item description field or override field
	
				case "text" :
					$html .= '<div class="description' . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
	
					// add label?
	
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					$html .= $item -> text . '</div>';
					break;
	
				case 'created_by':
					
					$html .= '<div class="author ' . $field -> name . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					$html .= $this-> jObject -> params -> get('item_writtenby_label', '') . $field -> display . '</div>';
					
					break;
					
				// display any other field
	
				default :
					$html .= '<div class="field field_' . $field -> name . (property_exists($css_fields, $field -> name) ? ' ' . $css_fields -> {$field -> name} : '') . '">';
					if ($field -> label) {
						$html .= '<div class="label">' . $field -> label . '</div>';
					}
	
					$html .= $field -> display . '</div>';
					break;
			}

			$html = iconv("UTF-8", "ASCII//TRANSLIT", $html);

			// field pretext
			$html .= method_exists($this -> tmplCustomObject,'customFieldRenderingPosttext') ? $this -> tmplCustomObject -> customFieldRenderingPosttext($item, $field, $group) : '';

		}

		// add addthis toolbar for this item?
		// to do: we need to add some parameters that indicate the configuration of the addthis bar
		if ($this-> jObject -> params -> get('items_addthis', 0) && $this-> jObject -> params -> get('items_addthis_after', '') == $field -> name) {
			$html .= '<div class="addthis_toolbox addthis_default_style " addthis:url="' . JURI::root() . substr($item_link, 1) . '">' . 
				$this-> jObject -> params -> get('items_addthis_services','<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a><a class="addthis_button_tweet"></a><a class="addthis_counter addthis_pill_style"></a>') .  
				'</div>';
		}

		// add disqus link?
		if ($this-> jObject -> params -> get('items_disqus', 0) && $this-> jObject -> params -> get('items_disqus_after', '') == $field -> name) {
			$html .= '<div class="disqus_comments"><a href="' . JURI::root() . substr($item_link, 1) . '#disqus_thread">Comments</a></div>';
		}
		
		return $html;
	}

	function renderJSONcat () {
		// Set mime type to JSON
		$doc = &JFactory::getDocument();
		$doc -> setMimeEncoding('application/json');
		
		// get category params
		$category = $this-> jObject -> category;
		$catparams = json_decode($category -> params);
		
		// generate urls
		$tmpl = JFactory::getApplication() -> input -> get('tmpl');
		$clayout = JFactory::getApplication() -> input -> get('clayout');
		$limit = JFactory::getApplication() -> input -> get('limit', $this-> jObject -> pageNav -> limit);
		$limitstart = JFactory::getApplication() -> input -> get('limitstart', 0);
		$url = trim(JURI::base(), "/") . JRoute::_(FlexicontentHelperRoute::getCategoryRoute($this-> jObject -> category -> id));
		$url_json = $url . '?clayout=' . $clayout . '&tmpl=' . $tmpl . '&limit=' . $limit;
		$url_prev = $url_json . '&limitstart=' . ($limitstart - $limit);
		$url_next = $url_json . '&limitstart=' . ($limitstart + $limit);
		
		$json = array('layout' => 'category');
		if($this-> jObject -> params -> get ('display_cat_id', 1)) $json['id'] = $category -> id;
		if($this-> jObject -> params -> get ('display_cat_title', 1)) $json['title'] = $category -> title;
		if($this-> jObject -> params -> get ('display_cat_alias', 1)) $json['alias'] = $category -> alias;
		if($this-> jObject -> params -> get ('display_cat_description', 1)) $json['description'] = $category -> description;
		if($this-> jObject -> params -> get ('display_cat_image', 1)) $json['image'] = $catparams -> image ? JURI::base() . $catparams -> image : '';
		if($this-> jObject -> params -> get ('display_cat_created', 1)) $json['created'] = $category -> created_time;
		if($this-> jObject -> params -> get ('display_cat_modified', 1)) $json['modified'] = $category -> modified_time;
		if($this-> jObject -> params -> get ('display_cat_metadesc', 1)) $json['metadesc'] = $category -> metadesc;
		if($this-> jObject -> params -> get ('display_cat_metakey', 1)) $json['metakey'] = $category -> metakey;
		if($this-> jObject -> params -> get ('display_cat_url', 1)) $json['url'] = $url;
		if($this-> jObject -> params -> get ('display_cat_json', 1)) $json['json'] = $url . (strpos($url, '?') ? '&' : '?') . 'clayout=' . $clayout . '&tmpl=' . $tmpl;
		if($this-> jObject -> params -> get ('display_cat_params', 0)) $json['params'] = $catparams;

		$json['total_items'] = $this-> jObject -> pageNav -> total;
		$json['items_per_page'] = $this-> jObject -> pageNav -> limit;
		$json['current_page'] = $this-> jObject -> pageNav -> pagesCurrent;
		$json['total_pages'] = $this-> jObject -> pageNav -> pagesTotal;
		$json['prev_page'] = ($this-> jObject -> pageNav -> pagesCurrent <= 1 ? '' : $url_prev);
		$json['next_page'] = ($this-> jObject -> pageNav -> pagesCurrent >= $this-> jObject -> pageNav -> pagesTotal ? '' : $url_next);
		
		// process category items
		$json['items'] = array();
		
		foreach($this-> jObject -> items as $item) {
			$url = trim(JURI::base(), "/") . JRoute::_(FlexicontentHelperRoute::getItemRoute($item -> slug, $item -> categoryslug));
			$json_item = array();
			$json_item['layout'] = 'item';
			if($this-> jObject -> params -> get ('display_item_id', 1)) $json_item['id'] = $item -> id;
			if($this-> jObject -> params -> get ('display_item_title', 1)) $json_item['title'] = $item -> title;
			if($this-> jObject -> params -> get ('display_item_alias', 1)) $json_item['alias'] = $item -> alias;
			if($this-> jObject -> params -> get ('display_item_author', 1)) $json_item['author'] = $item -> author;
			if($this-> jObject -> params -> get ('display_item_description', 1)) $json_item['description'] = $item -> text;
			if($this-> jObject -> params -> get ('display_item_created', 1)) $json_item['created'] = $item -> created;
			if($this-> jObject -> params -> get ('display_item_modified', 1)) $json_item['modified'] = $item -> modified;
			if($this-> jObject -> params -> get ('display_item_metadesc', 1)) $json_item['metadesc'] = $item -> metadesc;
			if($this-> jObject -> params -> get ('display_item_metakey', 1))  $json_item['metakey'] = $item -> metakey;
			if($this-> jObject -> params -> get ('display_item_url', 1)) $json_item['url'] = $url;
			if($this-> jObject -> params -> get ('display_item_json', 1)) $json_item['json'] = $url . (strpos($url, '?') ? '&' : '?') . 'ilayout=' . $clayout . '&tmpl=' . $tmpl;
			if($this-> jObject -> params -> get ('display_item_params', 0)) $json_item['params'] = json_decode($item -> params);
			if($this-> jObject -> params -> get ('display_item_fields', 1)) {
				$fields = array();
				if(isset($item -> positions)) {
					foreach($item -> positions as $position) {
						foreach($position as $field) {
							$fields[$field -> name] = array();
							if($this-> jObject -> params -> get ('display_item_field_label', 1)) $fields[$field -> name]['label'] = $field -> label;
							if($this-> jObject -> params -> get ('display_item_field_value', 1)) {
								$fields[$field -> name]['value'] = $item -> fields[$field -> name] -> iscore ? $item -> {$field -> name} : $item -> fieldvalues [$field -> id];
								// process serialized data
								if(is_array($fields[$field -> name]['value'])) {
									foreach($fields[$field -> name]['value'] as $value_idx => $value) {
										$value = @unserialize($value);
										if($value) $fields[$field -> name]['value'][$value_idx] = $value;
									}
								}
								else {
									$value = @unserialize($fields[$field -> name]['value']);
									if($value) $fields[$field -> name]['value'] = $value;
								}
							}
							if($this-> jObject -> params -> get ('display_item_field_display', 1)) $fields[$field -> name]['display'] = $field -> display;
						}
					}
				}
				$json_item['fields'] = $fields;
			}
			$json['items'][] = $json_item;
		}
		
		if (JFactory::getApplication() -> input -> get('callback', '') != '') {
			return JFactory::getApplication() -> input -> get('callback') . '(' . json_encode($json) . ')';
		}
		else {
			return json_encode($json);
		}
	}
	
	function renderJSONitem () {
		// Set mime type to JSON
		$doc =& JFactory::getDocument();
		$doc->setMimeEncoding('application/json');
		
		$item = $this-> jObject -> item;
		
		$url = trim(JURI::base(), "/") . JRoute::_(FlexicontentHelperRoute::getItemRoute($item -> slug, $item -> categoryslug));
		$json = array();
		$json['layout'] = 'item';
		if($this-> jObject -> params -> get ('display_item_id', 1)) $json['id'] = $item -> id;
		if($this-> jObject -> params -> get ('display_item_title', 1)) $json['title'] = $item -> title;
		if($this-> jObject -> params -> get ('display_item_alias', 1)) $json['alias'] = $item -> alias;
		if($this-> jObject -> params -> get ('display_item_author', 1)) $json['author'] = $item -> author;
		if($this-> jObject -> params -> get ('display_item_description', 1)) $json['description'] = $item -> text;
		if($this-> jObject -> params -> get ('display_item_created', 1)) $json['created'] = $item -> created;
		if($this-> jObject -> params -> get ('display_item_modified', 1)) $json['modified'] = $item -> modified;
		if($this-> jObject -> params -> get ('display_item_metadesc', 1)) $json['metadesc'] = $item -> metadesc;
		if($this-> jObject -> params -> get ('display_item_metakey', 1)) $json['metakey'] = $item -> metakey;
		if($this-> jObject -> params -> get ('display_item_url', 1)) $json['url'] = $url;
		if($this-> jObject -> params -> get ('display_item_json', 1)) $json['json'] = $url . (strpos($url, '?') ? '&' : '?') . 'iclayout=' . JFactory::getApplication() -> input -> get('ilayout') . '&tmpl=' . JFactory::getApplication() -> input -> get('tmpl');
		if($this-> jObject -> params -> get ('display_item_params', 0)) $json['params'] = json_decode($item -> params);
		if($this-> jObject -> params -> get ('display_item_fields', 1)) {
			$fields = array();
			if(isset($item -> positions)) {
				foreach($item -> positions as $position) {
					foreach($position as $field) {
						$fields[$field -> name] = array();
						if($this-> jObject -> params -> get ('display_item_field_label', 1)) $fields[$field -> name]['label'] = $field -> label;
						if($this-> jObject -> params -> get ('display_item_field_value', 1)) $fields[$field -> name]['value'] = $item -> fields[$field -> name] -> iscore ? $item -> {$field -> name} : $item -> fieldvalues [$field -> id];
						if($this-> jObject -> params -> get ('display_item_field_value', 1)) {
							$fields[$field -> name]['value'] = $item -> fields[$field -> name] -> iscore ? $item -> {$field -> name} : $item -> fieldvalues [$field -> id];
							// process serialized data
							if(is_array($fields[$field -> name]['value'])) {
								foreach($fields[$field -> name]['value'] as $value_idx => $value) {
									$value = @unserialize($value);
									if($value) $fields[$field -> name]['value'][$value_idx] = $value;
								}
							}
							else {
								$value = @unserialize($fields[$field -> name]['value']);
								if($value) $fields[$field -> name]['value'] = $value;
							}
						}
						if($this-> jObject -> params -> get ('display_item_field_display', 1)) $fields[$field -> name]['display'] = $field -> display;
						
					}
				}
			}
			$json['fields'] = $fields;
		}
		
		if (JFactory::getApplication() -> input -> get('callback', '') != '') {
			return JFactory::getApplication() -> input -> get('callback') . '(' . json_encode($json) . ')';
		}
		else {
			return json_encode($json);
		}
		

	}

	function getCatImage($image_src, $image_width = 240, $image_height = 240, $image_resize = 1) {

		// prepare resized image url

		$w		= '&amp;w=' . $image_width;
		$h		= '&amp;h=' . $image_height;
		$aoe	= '&amp;aoe=1';
		$q		= '&amp;q=95';
		$ar 	= '&amp;ar=x';
		$zc		= $image_resize ? '&amp;zc=1' : '';
		$ext    = strtolower(pathinfo($image_src, PATHINFO_EXTENSION));
		$f      = in_array( $ext, array('png', 'ico', 'gif') ) ? '&amp;f='.$ext : '';
		$conf	= $w . $h . $aoe . $q . $ar . $zc . $f;

		$base_url = (!preg_match("#^http|^https|^ftp#i", $image_src)) ? JURI::base(true) . '/' : '';
		$image_url = JURI::base(true) . '/components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . $base_url . $image_src . $conf;
		
		return $image_url;

	}

	function getItemImage(&$item, $fieldname, $image_size, $image_width = null, $image_height = null, $image_resize = null) {
					
		$url = '';
		$value = '';

		if (isset($item -> fieldvalues[$item -> fields[$fieldname] -> id])) {

			// Unserialize value's properties and check for empty original name property
			$value = unserialize($item -> fieldvalues[$item -> fields[$fieldname] -> id][0]);
			$image_name = trim(@$value['originalname']);

			if (strlen($image_name)) {

				$field = $item -> fields[$fieldname];
				$field -> parameters = json_decode($field -> attribs, true);
				$image_source = $field -> parameters['image_source'];
				$dir_url = str_replace('\\', '/', $field -> parameters['dir']);
				$multiple_image_usages = !$image_source && $field -> parameters['list_all_media_files'] && $field -> parameters['unique_thumb_method'] == 0;
				$extra_prefix = $multiple_image_usages ? 'fld' . $field -> id . '_' : '';
				$of_usage = $field -> untranslatable ? 1 : $field -> parameters['of_usage'];
				$u_item_id = ($of_usage && $item -> lang_parent_id && $item -> lang_parent_id != $item -> id) ? $item -> lang_parent_id : $item -> id;
				$extra_folder = '/item_' . $u_item_id . '_field_' . $field -> id;
						
				if ($image_size == 'custom') {
					
					// get the original image file path
					$image_file = JPATH_SITE . '/';
					
					// supports only db mode and item-field folder mode
					if ($image_source == 0) {
						// db mode
						$cparams = JComponentHelper::getParams('com_flexicontent');
						$image_file .= str_replace('\\', '/', $cparams -> get('file_path', 'components/com_flexicontent/uploads'));
					} else if ($image_source == 1) {
						// item+field specific folder
						$image_file .= $dir_url . $extra_folder . '/original';
					}
					
					$image_file .= '/' .  $image_name;

					$w		= '&amp;w=' . $image_width;
					$h		= '&amp;h=' . $image_height;
					$aoe	= '&amp;aoe=1';
					$q		= '&amp;q=95';
					$ar 	= '&amp;ar=x';
					$zc		= $image_resize ? '&amp;zc=1' : '';
					$ext    = strtolower(pathinfo($image_file, PATHINFO_EXTENSION));
					$f      = in_array( $ext, array('png', 'ico', 'gif') ) ? '&amp;f='.$ext : '';
					$conf	= $w . $h . $aoe . $q . $ar . $zc . $f;

					$url = JURI::root(true) . '/components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . urlencode($image_file) . $conf;
					
				}
				
				else {
					
					// Create thumbs URL path
					$url = JURI::root(true) . '/' . $dir_url;
					
					// Extra thumbnails sub-folder
					if ($image_source == 1) {
						// item+field specific folder
						$url .= $extra_folder;
					}

					$url .= '/' . $image_size . '_' . $extra_prefix . $image_name;
					
				}

			}

			$value['url'] = $url;

		}

		return $value;
	}
}
