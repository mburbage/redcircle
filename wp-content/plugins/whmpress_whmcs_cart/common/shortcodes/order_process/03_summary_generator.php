<?php defined( 'ABSPATH' ) or die( "Cannot access pages directly." );
$cart             = whcom_get_cart();
$tax_settings     = whcom_get_whmcs_setting();
$tax_rates        = whcom_get_tax_levels();
$domain_addons    = whcom_get_domain_addons();
$current_discount = whcom_get_current_promo();
$counter          = 0;
$totals           = [
	'discount'     => 0.00,
	'monthly'      => false,
	'quarterly'    => false,
	'semiannually' => false,
	'annually'     => false,
	'biennially'   => false,
	'triennially'  => false,
	'base_price'   => 0.00,
	'l1_amount'    => 0.00,
	'l2_amount'    => 0.00,
	'final_price'  => 0.00,
];
$summary_html     = [
	'full'             => '',
	'side'             => '',
	'short'            => '',
	'button'           => '',
	'discount_message' => ''
];


ob_start(); ?>


<ul class="whcom_list whcom_list_padded whcom_list_stripped">

	<?php if ( ! empty( $cart['all_items'] ) ) { ?>
		<?php foreach ( $cart['all_items'] as $cart_index => $cart_item ) {
			$found_item  = $product = $addon = $service = false;
			$domain_text = $domain_details = [];
			$tld         = '';
			$found_array = [
				'product'             => false,
				'product_domain'      => false,
				'product_addons'      => false,
				'product_options'     => false,
				'product_domain_free' => false,
				'addon'               => false,
				'domain'              => false,
				'renew_domain'        => false,
			];
			// Check if domain is found
			if ( ! empty( $cart_item['domain'] ) ) {
				$tld            = whcom_get_tld_from_domain( $cart_item['domain'] );
				$domain_details = whcom_get_tld_details( $tld );
				if ( ! empty( $domain_details ) ) {
					if ( ( ! empty( $cart_item['pid'] ) && (int) $cart_item['pid'] > 0 ) ) {
						$found_array['product_domain'] = true;
					}
					else {
						$found_array['domain'] = true;
					}
					$found_item = true;
				}
			}
			// Check if product is found
			if ( ! empty( $cart_item['pid'] ) && (int) $cart_item['pid'] > 0 ) {
				$product = whcom_get_product_details( $cart_item['pid'] );
				if ( $product ) {
					$found_array['product'] = $found_item = true;
					if ( ! empty( $cart_item['configoptions'] ) ) {
						$found_array['product_options'] = ( empty( $cart_item['configoptions'] ) ) ? [] : $cart_item['configoptions'];
					}
					if ( ! empty( $cart_item['addons'] ) ) {
						$found_array['product_addons'] = ( empty( $cart_item['addons'] ) ) ? [] : explode( ',', (string) $cart_item['addons'] );
					}
					if ( $found_array['product_domain'] ) {
						$free_domain_billingcycles = explode( ',', $product['freedomainpaymentterms'] );
						$free_domain_tlds          = explode( ',', $product['freedomaintlds'] );
						if (
							( (string) $product['freedomain'] == 'on' || (string) $product['freedomain'] == 'once' )
							&& ( in_array( $tld, $free_domain_tlds ) )
							&& ( in_array( $cart_item['billingcycle'], $free_domain_billingcycles ) )
							&& ( in_array( $cart_item['billingcycle'], $free_domain_billingcycles ) )

						) {
							$found_array['product_domain_free'] = true;
						}
					}
				}
			}

			if ( ! $found_item ) {
				continue;
			}

			?>

			<?php // Product ?>
			<?php if ( $found_array['product'] ) { ?>
				<?php
				$product_price = (float) $product['all_prices'][ $cart_item['billingcycle'] ]['price'];
				$product_setup = (float) $product['all_prices'][ $cart_item['billingcycle'] ]['setup'];
				?>
                <li>
                    <div class="whcom_op_summary_item_container" id="whcom_op_summary_item_product_<?php echo $cart_item['pid'] ?>">
                        <a href="#" class="whcom_op_delete_cart_item" data-cart-index="<?php echo $cart_index ?>"><span class="whcom_icon_cancel"></span></a>

                        <div class="whcom_row">
                            <div class="whcom_col_sm_7">
                                <strong class="whcom_summary_product_title"><?php echo $product['name']; ?></strong><br>
                                <span class="whcom_text_small whcom_summary_product_group_title"><?php echo $product['group_name']; ?></span>
								<?php if ( ! empty( $cart_item['domain'] ) ) { ?>
                                    <div class="whcom_text_small whcom_summary_product_domain">
                                        <a href="<?php echo $cart_item['domain']; ?>" target="_blank"><?php echo $cart_item['domain']; ?></a>
                                    </div>
								<?php } ?>
								<?php if ( ! empty( $cart_item['hostname'] ) ) { ?>
                                    <div class="whcom_text_small whcom_summary_product_hostname">
										<?php echo $cart_item['hostname']; ?>
                                    </div>
								<?php } ?>
								<?php if ( $found_array['product_options'] ) { ?>
                                    <div class="whcom_text_small whcom_summary_product_configoptions whcom_padding_0_10">
										<?php foreach ( $found_array['product_options'] as $option_id => $sub_option_id ) { ?>
											<?php
											$curr_option         = $product['prd_configoptions'][ $option_id ];
											$curr_option_html    = $curr_option['optionname'] . ' ';
											$configoption_amount = 0.00;
											$configoption_setup  = 0.00;
											?>
											<?php switch ( $curr_option['optiontype'] ) {
												case '1' :
													{
														$curr_sub_option     = $curr_option['sub_options'][ $sub_option_id ];
														$curr_option_html    .= '(' . whcom_format_amount( $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'] ) . ')';
														$configoption_amount = (float) $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'];
														$configoption_setup  = (float) $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['setup'];
														break;
													}
												case '2' :
													{
														$curr_sub_option     = $curr_option['sub_options'][ $sub_option_id ];
														$curr_option_html    .= '(' . whcom_format_amount( $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'] ) . ')';
														$configoption_amount = (float) $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'];
														$configoption_setup  = (float) $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['setup'];
														break;
													}
												case '3' :
													{
														$curr_sub_option = reset( $curr_option['sub_options'] );
														if ( $sub_option_id > 0 ) {
															$curr_option_html    .= '(' . whcom_format_amount( $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'] ) . ')';
															$configoption_amount = (float) $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'];
															$configoption_setup  = (float) $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['setup'];
														}
														break;
													}
												case '4' :
													{
														$curr_sub_option  = reset( $curr_option['sub_options'] );
														$curr_option_html .= '(';
														$curr_option_html .= whcom_format_amount( $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'] ) . ' x ';
														$curr_option_html .= $sub_option_id;
														$curr_option_html .= ')';

														$configoption_amount = (float) ( $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['price'] * $sub_option_id );
														$configoption_setup  = (float) ( $curr_sub_option['all_prices'][ $cart_item['billingcycle'] ]['setup'] * $sub_option_id );
														break;
													}
												default :
													{
														$curr_sub_option = [];
													}
											}
											?>
                                            <div class="whcom_summary_product_configoption">
                                                <i class="whcom_icon_angle-double-right"></i> <?php echo $curr_option_html ?>
                                            </div>
											<?php $product_price = $product_price + $configoption_amount; ?>
											<?php $product_setup = $product_setup + $configoption_setup; ?>
										<?php } ?>
                                    </div>
								<?php } ?>
                            </div>
                            <div class="whcom_col_sm_5 whcom_text_right">
                                <div class="whcom_op_summary_item_price whcom_summary_product_price <?php echo ( (float) $product_price > 0 ) ? '' : 'free'; ?>">
                                    <strong><?php echo whcom_format_amount( $product_price ) ?></strong>
                                </div>
                                <div class="whcom_text_small whcom_summary_product_billingcycle">
									<?php echo whcom_convert_billingcycle( $cart_item['billingcycle'] ); ?>
                                </div>
                                <div class="whcom_text_small whcom_summary_product_setup <?php echo ( (float) $product_setup > 0 ) ? '' : 'free'; ?>">
									<?php echo whcom_format_amount( $product_setup ) ?>
									<?php esc_html_e( 'Setup Fee', 'whcom' ) ?>
                                </div>
                            </div>
							<?php // Final Array Population -> product


							// If product is discounted
							if ( whcom_validate_item_promotion( 'product', $product['id'], $cart_item['billingcycle'] ) ) {
								$product_discounts  = whcom_apply_item_discount( $product_price, $product_setup );
								$product_price      = $product_discounts['price'];
								$product_setup      = $product_discounts['setup'];
								$totals['discount'] = $totals['discount'] + $product_discounts['discount'];
							}

							$totals[ $cart_item['billingcycle'] ] = $product_price;
							$product_total                        = $product_price + $product_setup;
							if ( ! empty( $product['tax'] ) && $product['tax'] == '1' ) {
								$product_total         = whcom_calculate_tax( $product_total, $tax_settings );
								$totals['base_price']  = $totals['base_price'] + $product_total['base_price'];
								$totals['l1_amount']   = $totals['l1_amount'] + $product_total['l1_amount'];
								$totals['l2_amount']   = $totals['l2_amount'] + $product_total['l2_amount'];
								$totals['final_price'] = $totals['final_price'] + $product_total['final_price'];
							}
							else {
								$totals['base_price']  = $totals['base_price'] + $product_total;
								$totals['final_price'] = $totals['final_price'] + $product_total;
							}
							?>
                        </div>


                    </div>
                </li>
				<?php
				ob_start(); ?>
                <li class="whcom_op_summary_short_item whcom_row whcom_row_no_gap">
                    <span class="whcom_col_7">
                        <?php echo $product['name']; ?>
                    </span>
                    <span class="whcom_col_5 whcom_text_right">
                        <?php echo whcom_format_amount( $product_price + $product_setup ); ?>
                    </span>
                </li>
				<?php
				$summary_html['short'] .= ob_get_clean();
				?>
			<?php } ?>

			<?php // Addons with product ?>
			<?php if ( $found_array['product_addons'] ) { ?>
				<?php foreach ( $found_array['product_addons'] as $addon_id ) { ?>
                    <li>
                        <div class="whcom_op_summary_item_container" id="whcom_op_summary_item_product_addon_<?php echo $addon_id ?>">
                            <div class="whcom_row">
                                <div class="whcom_col_sm_7">
									<?php
									$curr_addon = $product['prd_addons'][ $addon_id ];
									// Addon price logic
									$addon_billingcycle = strtolower( $curr_addon['billingcycle'] );
									if ( $addon_billingcycle == 'recurring' ) {
										if ( isset( $curr_addon[ $cart_item['billingcycle'] ] ) && $curr_addon[ $cart_item['billingcycle'] ] >= 0 ) {
											$addon_billingcycle = $cart_item['billingcycle'];
											$curr_addon_price   = $curr_addon['all_prices'][ $addon_billingcycle ]['price'];
											$curr_addon_setup   = $curr_addon['all_prices'][ $addon_billingcycle ]['setup'];
										}
										else {
											reset( $curr_addon['lowest_price'] );
											$addon_billingcycle = key( $curr_addon['lowest_price'] );
											$curr_addon_price   = $curr_addon['lowest_price'][ $addon_billingcycle ]['price'];
											$curr_addon_setup   = $curr_addon['lowest_price'][ $addon_billingcycle ]['setup'];
										}
									}
                                    elseif ( $addon_billingcycle == 'free' ) {
										$curr_addon_price = 0.00;
										$curr_addon_setup = 0.00;
									}
									else {
										$curr_addon_price = $curr_addon['monthly'];
										$curr_addon_setup = $curr_addon['msetupfee'];
									}
									?>
                                    <div>
                                        <strong class="whcom_op_summary_item_product_addon_title"><?php echo $curr_addon['name']; ?></strong>
                                        <div class="whcom_text_small whcom_op_summary_item_product_addon_label">
											<?php esc_html_e( "Addon", "whcom" ); ?>
                                        </div>
                                        <div class="whcom_text_small whcom_op_summary_item_product_addon_setup">
											<?php echo whcom_format_amount( $curr_addon_setup ); ?>
											<?php esc_html_e( "Setup Fee", "whcom" ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="whcom_col_sm_5 whcom_text_right whcom_op_summary_item_product_addon_price">
									<?php echo whcom_format_amount( $curr_addon_price ); ?>
                                    <div class="whcom_text_small whcom_op_summary_item_product_addon_billingcycle">
										<?php echo whcom_convert_billingcycle( $addon_billingcycle ); ?>
                                    </div>
                                </div>

								<?php

								// If Product -> Addons is discounted
								if ( whcom_validate_item_promotion( 'addon', $addon_id, $addon_billingcycle ) ) {
									$curr_addon_discounts = whcom_apply_item_discount( $curr_addon_price, $curr_addon_setup );
									$curr_addon_price     = $curr_addon_discounts['price'];
									$curr_addon_setup     = $curr_addon_discounts['setup'];
									$totals['discount']   = $totals['discount'] + $curr_addon_discounts['discount'];
								}

								// Final Array Population -> product-addons
								$totals[ $addon_billingcycle ] = $totals[ $addon_billingcycle ] + $curr_addon_price;
								$curr_addon_total              = $curr_addon_price + $curr_addon_setup;

								if ( ! empty( $curr_addon['tax'] ) && $curr_addon['tax'] == '1' ) {
									$curr_addon_total      = whcom_calculate_tax( $curr_addon_total, $tax_settings );
									$totals['base_price']  = $totals['base_price'] + $curr_addon_total['base_price'];
									$totals['l1_amount']   = $totals['l1_amount'] + $curr_addon_total['l1_amount'];
									$totals['l2_amount']   = $totals['l2_amount'] + $curr_addon_total['l2_amount'];
									$totals['final_price'] = $totals['final_price'] + $curr_addon_total['final_price'];
								}
								else {
									$totals['base_price']  = $totals['base_price'] + $curr_addon_total;
									$totals['final_price'] = $totals['final_price'] + $curr_addon_total;
								}

								?>
                            </div>
                        </div>
                    </li>
					<?php
					ob_start(); ?>
                    <li class="whcom_op_summary_short_item whcom_row whcom_row_no_gap">
                        <span class="whcom_col_7">
                            <small> + <?php echo $curr_addon['name']; ?></small>
                        </span>
                        <span class="whcom_col_5 whcom_text_right">
                            <?php echo whcom_format_amount( $curr_addon_price + $curr_addon_setup ); ?>
                        </span>
                    </li>
					<?php
					$summary_html['short'] .= ob_get_clean();
					?>
				<?php } ?>
			<?php } ?>

			<?php // Domain, with or without product ?>
			<?php if ( ! empty( $domain_details ) ) { ?>
                <li>
                    <div class="whcom_op_summary_item_container whcom_op_summary_item_domain">
                        <a href="#" class="whcom_op_delete_cart_item" data-cart-index="<?php echo $cart_index ?>"><span class="whcom_icon_cancel"></span></a>
						<?php
						$domain_text  = whcom_generate_domain_text( $domain_details, $cart_item['domaintype'], $cart_item['regperiod'], $found_array['product_domain_free'], $cart_index );
						$domain_price = (float) $domain_text['price'];

						?>

                        <div class="whcom_op_summary_item_domains">
                            <div class="whcom_row">
                                <div class="whcom_col_sm_7">
                                    <strong class="whcom_op_summary_item_domain_label"><?php esc_html_e( 'Domain ', 'whcom' ) ?><?php echo ( $cart_item['domaintype'] == 'register' ) ? esc_html__( "Registration", "whcom" ) : ucfirst( $cart_item['domaintype'] ); ?></strong>
                                    <div class="whcom_text_small whcom_op_summary_item_domain_link">
                                        <a href="<?php echo $cart_item['domain']; ?>"
                                           target="_blank"><?php echo $cart_item['domain']; ?></a>
                                    </div>
                                    <div class="whcom_text_small whcom_padding_0_10 whcom_op_summary_item_domain_addons">
										<?php if ( ! empty( $cart_item['dnsmanagement'] ) ) { ?>
											<?php $domain_addon_price = (float) $domain_addons['dnsmanagement'] * (float) $cart_item['regperiod']; ?>
                                            <div class="whcom_op_summary_item_domain_dnsmanagement">
                                                <i class="whcom_icon_angle-double-right"></i>
												<?php esc_html_e( "DNS Management", 'whcom' ) ?>
                                                - <?php echo whcom_format_amount( $domain_addon_price ) . ' ' . $domain_text['text']; ?>
                                            </div>
											<?php $domain_price = $domain_price + (float) $domain_addon_price; ?>
										<?php } ?>
										<?php if ( ! empty( $cart_item['emailforwarding'] ) ) { ?>
											<?php $domain_addon_price = (float) $domain_addons['emailforwarding'] * (float) $cart_item['regperiod']; ?>
                                            <div class="whcom_op_summary_item_domain_emailforwarding">
                                                <i class="whcom_icon_angle-double-right"></i>
												<?php esc_html_e( "Email Forwarding", 'whcom' ) ?>
                                                - <?php echo whcom_format_amount( $domain_addon_price ) . ' ' . $domain_text['text']; ?>
                                            </div>
											<?php $domain_price = $domain_price + (float) $domain_addon_price; ?>
										<?php } ?>
										<?php if ( ! empty( $cart_item['idprotection'] ) ) { ?>
											<?php $domain_addon_price = (float) $domain_addons['idprotection'] * (float) $cart_item['regperiod']; ?>
                                            <div class="whcom_op_summary_item_domain_idprotection">
                                                <i class="whcom_icon_angle-double-right"></i>
												<?php esc_html_e( "ID Protection", 'whcom' ) ?>
                                                - <?php echo whcom_format_amount( $domain_addon_price ) . ' ' . $domain_text['text']; ?>
                                            </div>
											<?php $domain_price = $domain_price + (float) $domain_addon_price; ?>
										<?php } ?>
                                    </div>
                                </div>
                                <div class="whcom_col_sm_5 whcom_text_right whcom_op_summary_item_domain_price">
									<?php echo whcom_format_amount( $domain_price ); ?>
                                    <div class="whcom_op_summary_item_domain_text">
										<?php echo strtolower( $domain_text['text'] ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>


						<?php

						// If Domain is discounted
						if ( whcom_validate_item_promotion( 'domain', $domain_details['extension'], $cart_item['regperiod'] ) ) {
							$domain_discounts   = whcom_apply_item_discount( $domain_price, 0.00 );
							$domain_price       = $domain_discounts['price'];
							$totals['discount'] = $totals['discount'] + $domain_discounts['discount'];
						}

						$domain_price_bk = $domain_price;

						// Final Array Population -> domain
						if ( ! empty( $tax_settings['TaxDomains'] ) && $tax_settings['TaxDomains'] == 'on' ) {
							$domain_price          = whcom_calculate_tax( $domain_price, $tax_settings );
							$totals['base_price']  = $totals['base_price'] + $domain_price['base_price'];
							$totals['l1_amount']   = $totals['l1_amount'] + $domain_price['l1_amount'];
							$totals['l2_amount']   = $totals['l2_amount'] + $domain_price['l2_amount'];
							$totals['final_price'] = $totals['final_price'] + $domain_price['final_price'];
						}
						else {
							$totals['base_price']  = $totals['base_price'] + $domain_price;
							$totals['final_price'] = $totals['final_price'] + $domain_price;
						}

	                    ob_start(); ?>
                        <li class="whcom_op_summary_short_item whcom_row whcom_row_no_gap">
                            <span class="whcom_col_7">
                                <small>
                                    + <?php esc_html_e( 'Domain ', 'whcom' ) ?><?php echo ( $cart_item['domaintype'] == 'register' ) ? esc_html__( "Registration", "whcom" ) : ucfirst( $cart_item['domaintype'] ); ?>
                                </small>
                            </span>
                            <span class="whcom_col_5 whcom_text_right">
                                <?php echo whcom_format_amount( $domain_price_bk ); ?>
                            </span>
                        </li>
	                    <?php
	                    $summary_html['short'] .= ob_get_clean();
	                    ?>
                    </div>
                </li>
			<?php } ?>


			<?php $counter ++; ?>


		<?php } ?>
	<?php } ?>

	<?php if ( ! empty( $cart['order_specific']['domainrenewals'] ) ) { ?>
		<?php foreach ( $cart['order_specific']['domainrenewals'] as $renew_domain => $renew_period ) {
			$renew_domain_tld           = whcom_get_tld_from_domain( $renew_domain );
			$renew_domain_details       = whcom_get_tld_details( $renew_domain_tld );
			$domain_args                = [
				"action" => "GetClientsDomains",
				"domain" => $renew_domain,
			];
			$domain_service_details     = false;
			$domain_service_details_raw = whcom_process_api( $domain_args );
			if ( ! empty( $domain_service_details_raw['result'] ) && (string) $domain_service_details_raw['result'] == 'success' ) {
				foreach ( $domain_service_details_raw['domains']['domain'] as $domain_service_details_raw_domain ) {
					if ( $domain_service_details_raw_domain['status'] == 'Active' ) {
						$domain_service_details = $domain_service_details_raw_domain;
						break;
					}
				}
			}

			?>
			<?php if ( $renew_domain_details && $domain_service_details ) { ?>
                <li>
                    <div class="whcom_op_summary_item_container whcom_op_summary_item_domain_renewal">
						<?php
						$domain_renewal_text  = whcom_generate_domain_text( $renew_domain_details, 'renew', $renew_period, false, 0 );
						$domain_renewal_price = (float) $domain_renewal_text['price'];
						?>

                        <div class="whcom_op_summary_item_domains">
                            <div class="whcom_row">
                                <div class="whcom_col_sm_7">
                                    <strong class="whcom_op_summary_item_domain_renewal_label"><?php esc_html_e( 'Domain Renewal', 'whcom' ) ?></strong>
                                    <div class="whcom_text_small whcom_op_summary_item_domain_renewal_link">
                                        <a href="<?php echo $renew_domain; ?>" target="_blank"><?php echo $renew_domain; ?></a>
                                    </div>
                                    <div class="whcom_text_small whcom_op_summary_item_domain_renewal_addons whcom_padding_0_10">
										<?php if ( ! empty( $domain_service_details['dnsmanagement'] ) ) { ?>
											<?php $domain_addon_price = (float) $domain_addons['dnsmanagement'] * (float) $renew_period; ?>
                                            <div class="whcom_op_summary_item_domain_renewal_dnsmanagement">
                                                <i class="whcom_icon_angle-double-right"></i>
												<?php esc_html_e( "DNS Management", 'whcom' ) ?>
                                                - <?php echo whcom_format_amount( $domain_addon_price ) . ' ' . $domain_text['text']; ?>
                                            </div>
											<?php $domain_renewal_price = $domain_renewal_price + (float) $domain_addon_price; ?>
										<?php } ?>
										<?php if ( ! empty( $domain_service_details['emailforwarding'] ) ) { ?>
											<?php $domain_addon_price = (float) $domain_addons['emailforwarding'] * (float) $renew_period; ?>
                                            <div class="whcom_op_summary_item_domain_renewal_emailforwarding">
                                                <i class="whcom_icon_angle-double-right"></i>
												<?php esc_html_e( "Email Forwarding", 'whcom' ) ?>
                                                - <?php echo whcom_format_amount( $domain_addon_price ) . ' ' . $domain_text['text']; ?>
                                            </div>
											<?php $domain_renewal_price = $domain_renewal_price + (float) $domain_addon_price; ?>
										<?php } ?>
										<?php if ( ! empty( $domain_service_details['idprotection'] ) ) { ?>
											<?php $domain_addon_price = (float) $domain_addons['idprotection'] * (float) $renew_period; ?>
                                            <div class="whcom_op_summary_item_domain_renewal_idprotection">
                                                <i class="whcom_icon_angle-double-right"></i>
												<?php esc_html_e( "ID Protection", 'whcom' ) ?>
                                                - <?php echo whcom_format_amount( $domain_addon_price ) . ' ' . $domain_text['text']; ?>
                                            </div>
											<?php $domain_renewal_price = $domain_renewal_price + (float) $domain_addon_price; ?>
										<?php } ?>
                                    </div>
                                </div>
                                <div class="whcom_col_sm_5 whcom_text_right whcom_op_summary_item_domain_renewal_price">
									<?php echo whcom_format_amount( $domain_renewal_price ); ?>
                                    <div class="whcom_op_summary_item_domain_renewal_text">
										<?php echo strtolower( $domain_renewal_text['text'] ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>


						<?php

						// If Domain is discounted
						if ( whcom_validate_item_promotion( 'domain', $renew_domain_tld, $renew_period ) ) {
							$domain_renewal_discounts = whcom_apply_item_discount( $domain_renewal_price, 0.00 );
							$domain_renewal_price     = $domain_renewal_discounts['price'];
							$totals['discount']       = $totals['discount'] + $domain_renewal_discounts['discount'];
						}
						$domain_renewal_price_bk = $domain_renewal_price;

						// Final Array Population -> domain_renewal
						if ( ! empty( $tax_settings['TaxDomains'] ) && $tax_settings['TaxDomains'] == 'on' ) {
							$domain_renewal_price  = whcom_calculate_tax( $domain_renewal_price, $tax_settings );
							$totals['base_price']  = $totals['base_price'] + $domain_renewal_price['base_price'];
							$totals['l1_amount']   = $totals['l1_amount'] + $domain_renewal_price['l1_amount'];
							$totals['l2_amount']   = $totals['l2_amount'] + $domain_renewal_price['l2_amount'];
							$totals['final_price'] = $totals['final_price'] + $domain_renewal_price['final_price'];
						}
						else {
							$totals['base_price']  = $totals['base_price'] + $domain_renewal_price;
							$totals['final_price'] = $totals['final_price'] + $domain_renewal_price;
						}

						ob_start(); ?>
                        <li class="whcom_op_summary_short_item whcom_row whcom_row_no_gap">
                            <span class="whcom_col_7">
                                <?php echo strtolower( $domain_renewal_text['text'] ); ?>
                            </span>
                            <span class="whcom_col_5 whcom_text_right">
	                            <?php esc_html_e( 'Domain Renewal', 'whcom' ) ?>
                            </span>
                        </li>
	                    <?php
	                    $summary_html['short'] .= ob_get_clean();
						?>
                    </div>
					<?php $counter ++; ?>
                </li>
			<?php } ?>
		<?php } ?>
	<?php } ?>

	<?php
	// Addon only order
	if ( ! empty( $cart['order_specific']['addonids'] ) && is_array( $cart['order_specific']['addonids'] ) && ! empty( $cart['order_specific']['serviceids'] ) && is_array( $cart['order_specific']['serviceids'] ) ) {
		$addon_ids   = $cart['order_specific']['addonids'];
		$service_ids = $cart['order_specific']['serviceids'];
		if ( count( $addon_ids ) == count( $service_ids ) ) {
			foreach ( $addon_ids as $addon_index => $addon_id ) {
				$addon   = whcom_get_addon_details( $addon_id );
				$service = whcom_get_service_details( $service_ids[ $addon_index ] );
				if ( ! empty( $addon ) && ! empty( $service ) ) { ?>
					<?php // Addon only ?>
                    <li>
						<?php
						// Addon price logic
						$addon_duration   = strtolower( $addon['billingcycle'] );
						$service_duration = strtolower( $service['billingcycle'] );
						if ( $addon_duration == 'recurring' ) {
							if ( isset( $curr_addon[ $cart_item['billingcycle'] ] ) && $addon[ $service_duration ] >= 0 ) {
								$addon_duration = $service_duration;
								$addon_price    = $addon['all_prices'][ $addon_duration ]['price'];
								$addon_setup    = $addon['all_prices'][ $addon_duration ]['setup'];
							}
							else {
								reset( $addon['lowest_price'] );
								$addon_duration = key( $addon['lowest_price'] );
								$addon_price    = $addon['lowest_price'][ $addon_duration ]['price'];
								$addon_setup    = $addon['lowest_price'][ $addon_duration ]['setup'];
							}
						}
                        elseif ( $addon_duration == 'free' ) {
							$addon_price = 0.00;
							$addon_setup = 0.00;
						}
						else {
							$addon_price = $addon['monthly'];
							$addon_setup = $addon['msetupfee'];
						}

						?>
                        <div class="whcom_op_summary_item_container" id="whcom_op_summary_item_addon_<?php echo $addon_id ?>">
                            <div class="whcom_row">
                                <div class="whcom_col_sm_7">
                                    <div class="whcom_op_summary_item_addon_title">
                                        <strong><?php echo $addon['name']; ?></strong></div>
									<?php if ( ! empty( $service['name'] ) ) { ?>
                                        <div class="whcom_text_small whcom_op_summary_item_addon_service_title"><?php echo $service['name']; ?></div>
									<?php } ?>
									<?php if ( ! empty( $service['domain'] ) ) { ?>
                                        <div class="whcom_text_small whcom_op_summary_item_addon_domain"><?php echo $service['domain']; ?></div>
									<?php } ?>
                                </div>
                                <div class="whcom_col_sm_5 whcom_text_right">
                                    <div class="whcom_op_summary_item_price whcom_op_summary_item_addon_price">
                                        <strong class="">
											<?php echo whcom_format_amount( $addon_price ) ?> +
											<?php echo whcom_format_amount( $addon_setup ) ?>
											<?php esc_html_e( 'Setup Fee', 'whcom' ) ?>
                                        </strong>
                                    </div>
                                    <div class="whcom_text_small whcom_op_summary_item_addon_billingcycle">
										<?php echo whcom_convert_billingcycle( $addon_duration ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

						<?php

						// If Addons (Addon only) is discounted
						if ( whcom_validate_item_promotion( 'addon', $addon['id'], $addon_duration ) ) {
							$addon_discounts    = whcom_apply_item_discount( $addon_price, $addon_setup );
							$addon_price        = $addon_discounts['price'];
							$addon_setup        = $addon_discounts['setup'];
							$totals['discount'] = $totals['discount'] + $addon_discounts['discount'];
						}


						// Final Array Population -> addon-only
						$totals[ $addon_duration ] = $totals[ $addon_duration ] + $addon_price;
						$addon_total               = $addon_price + $addon_setup;
						if ( ! empty( $addon['tax'] ) && $addon['tax'] == '1' ) {
							$addon_total           = whcom_calculate_tax( $addon_total, $tax_settings );
							$totals['base_price']  = $totals['base_price'] + $addon_total['base_price'];
							$totals['l1_amount']   = $totals['l1_amount'] + $addon_total['l1_amount'];
							$totals['l2_amount']   = $totals['l2_amount'] + $addon_total['l2_amount'];
							$totals['final_price'] = $totals['final_price'] + $addon_total['final_price'];
						}
						else {
							$totals['base_price']  = $totals['base_price'] + $addon_total;
							$totals['final_price'] = $totals['final_price'] + $addon_total;
						}
						ob_start(); ?>
                        <li class="whcom_op_summary_short_item">
                            <span><?php echo $addon['name']; ?></span>
                            <span><?php echo $addon_total; ?></span>
                        </li>
                        <?php
                        $summary_html['short'] .= ob_get_clean();
                        ?>
                    </li>
					<?php $counter ++; ?>
				<?php }
			}
		}


	}


	?>

</ul>

<?php if ( $counter < 1 ) { ?>
    <div class="whcom_alert whcom_alert_info">
		<?php esc_html_e( 'Your Shopping Cart is Empty', 'whcom' ); ?>
    </div>
<?php } ?>



<?php ob_start() ?>
<div class="whcom_cart_summary_sidebar">

    <div class="whcom_clearfix whcom_margin_bottom_5 whcom_padding_5_0 whcom_bordered_bottom">
        <span class="whcom_pull_left"><?php esc_html_e( 'Subtotal', 'whcom' ) ?></span>
        <span class="whcom_pull_right"><?php echo whcom_format_amount( $totals['base_price'] + $totals['discount'] ); ?></span>
    </div>
	<?php if ( $totals['l1_amount'] > 0 || $totals['l2_amount'] > 0 || $totals['discount'] > 0 ) { ?>
        <div class="whcom_padding_10_0 whcom_bordered_bottom">
			<?php if ( $totals['discount'] > 0 ) { ?>
				<?php
				$discount_value = $current_discount['value'];
				$discount_type  = esc_html__( "One Time Discount", "whcom" );
				if ( $current_discount['recurring'] == '1' ) {
					$discount_type = esc_html__( "Recurring Discount", "whcom" );
				}

				switch ( $current_discount['type'] ) {
					case 'Free Setup' :
						{
							$discount_value = esc_html__( "Free Setup", "whcom" );
							break;
						}
					case 'Fixed Amount' :
						{
							$discount_value = whcom_format_amount( $current_discount['value'] );
							break;
						}
					case 'Price Override' :
						{
							$discount_value = whcom_format_amount( $current_discount['value'] ) . ' ' . esc_html__( "Price Override", "whcom" );
							break;
						}
					case 'Percentage' :
						{
							$discount_value = $current_discount['value'] . esc_html__( "%", "whcom" );
							break;
						}
					default :
						{

						}
				}


				?>
				<?php ?>
                <div class="whcom_clearfix">
                    <span class="whcom_pull_left">
						<?php echo $discount_value; ?>
						<?php echo $discount_type; ?>
                    </span>
                    <span class="whcom_pull_right"><?php echo whcom_format_amount( $totals['discount'] ); ?></span>
                </div>
			<?php } ?>
			<?php if ( $totals['l1_amount'] > 0 ) { ?>

                <div class="whcom_clearfix">
                    <span class="whcom_pull_left"><?php echo $tax_rates['level1_title'] ?>
                        &#64; <?php echo $tax_rates['level1_rate'] ?>&#37;</span>
                    <span class="whcom_pull_right"><?php echo whcom_format_amount( $totals['l1_amount'] ); ?></span>
                </div>
			<?php } ?>
			<?php if ( $totals['l2_amount'] > 0 ) { ?>

                <div class="whcom_clearfix">
                    <span class="whcom_pull_left"><?php echo $tax_rates['level2_title'] ?>
                        &#64; <?php echo $tax_rates['level2_rate'] ?>&#37;</span>
                    <span class="whcom_pull_right"><?php echo whcom_format_amount( $totals['l2_amount'] ); ?></span>
                </div>
			<?php } ?>
        </div>
	<?php } ?>
    <div class="whcom_clearfix whcom_margin_bottom_30">
        <div class="whcom_pull_left_sm">
            <span class="whcom_pull_left"><?php esc_html_e( 'Totals', 'whcom' ) ?></span>
        </div>
        <div class="whcom_pull_right_sm">
			<?php
			$billingcycles = [
				'monthly',
				'quarterly',
				'semiannually',
				'annually',
				'biennially',
				'triennially',
			];
			?>
			<?php foreach ( $totals as $key => $total ) { ?>
				<?php if ( $total && in_array( $key, $billingcycles ) ) { ?>
                    <div class="whcom_clearfix">
                        <div class="whcom_pull_right">
                            <span><?php echo whcom_format_amount( $total ); ?></span>
                            <span><?php echo whcom_convert_billingcycle( $key ) ?></span>
                        </div>
                    </div>
				<?php } ?>
			<?php } ?>
        </div>
    </div>
    <div class="whcom_clearfix">
        <div class="whcom_text_right whcom_text_2x">
			<?php echo whcom_format_amount( [ 'amount' => $totals['final_price'], 'add_suffix' => 'yes' ] ); ?>
        </div>
        <div class="whcom_text_right"><?php esc_html_e( 'Total Due Today', 'whcom' ) ?></div>
    </div>
</div>
<?php $summary_html['side'] = ob_get_clean(); ?>




<?php ob_start(); ?>

<?php if ( ! empty ( $cart['order_specific']['promocode'] ) ) { ?>
	<?php if ( (float) $totals['discount'] > 0.00 ) { ?>
        <div class="whcom_alert whcom_alert_success">
			<?php esc_html_e( "Promotion Code Accepted! Your order total has been updated.", "whcom" ) ?>
        </div>
	<?php }
	else if ( ! empty( $current_discount ) ) { ?>
        <div class="whcom_alert whcom_alert_info">
			<?php esc_html_e( "The promotion code you entered has been applied to your cart but no items qualify for the discount yet - please check the promotion terms", "whcom" ) ?>
        </div>
	<?php }
	else { ?>
        <div class="whcom_alert whcom_alert_warning">
			<?php esc_html_e( "The promotion code entered does not exist", "whcom" ) ?>
        </div>
	<?php } ?>
<?php } ?>

<?php $summary_html['discount_message'] = ob_get_clean(); ?>

<?php ob_start() ?>
<?php echo $counter; ?> <?php esc_html_e( 'item(s)', 'whcom' ) ?> / <?php echo whcom_format_amount( $totals['final_price'] ); ?>

<?php $summary_html['button'] = ob_get_clean() ?>



<?php ob_start(); ?>


<li class="whcom_op_summary_short_total whcom_row whcom_row_no_gap">
    <div class="whcom_col_7">
        <strong>
		    <?php esc_html_e('Grand Total', 'whcom')?>
        </strong>
    </div>
    <div class="whcom_col_5 whcom_text_right">
        <strong>
		    <?php echo whcom_format_amount( $totals['final_price'] ); ?>
        </strong>
    </div>
</li>

<?php $summary_html['short'] .= ob_get_clean(); ?>


<?php

$summary_html['status']      = 'OK';
$summary_html['message']     = esc_html__( 'Updating Cart Summaries', 'whcom' );
$summary_html['detailed']    = ob_get_clean();
$summary_html['total_items'] = $counter;
$summary_html['grand_total'] = whcom_format_amount( $totals['final_price'] );
?>

