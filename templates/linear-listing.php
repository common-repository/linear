<?php
/**
 * The template for displaying specific apartment on sale.
 *
 * @package Linear
 */

namespace Linear\Templates;

function template_single_listing( $linear_data_id ){

	$listing = apply_filters( "linear_listing", $linear_data_id, substr( get_locale(), 0, 2 ) );
	$use_theme_fonts = boolval( get_linear_option_value( 'theme_fonts' ) );
	
	if( !$listing ){
		return '';
	}

	?>

	<div id="linear">
		<div class="<?php echo esc_attr( $use_theme_fonts ? '' : 'linear-u-font-base'); ?>">
			<div class="linear-o-container">

				<?php
					echo listing_pre_title( $listing );
					echo listing_image_carousel( $listing );
					echo listing_introduction( $listing );
					echo listing_actions( $listing );
					echo realtor(
						$listing
					);
				?>

				<div class="linear-o-row">
					<div class="linear-o-col-12">
						<?php echo listing_map( $listing ); ?>

						<?php
							//
							// Presentation
							//

							$title 		= esc_html__( 'Presentation', 'linear' );
							$sub_title 	= get_object_value( $listing, 'freeTextTitle', '' );
							$content 	= get_object_value( $listing, 'freeText', [] );
							
							echo accordion( $title, $sub_title, $content, 'simple' );

							//
							// Notes
							//

							$title 		= esc_html__( 'Notes', 'linear' );
							$sub_title 	= '';
							$content 	= get_object_value( $listing , 'notes', [] );
							
							echo accordion( $title, $sub_title, $content, 'simple' );

							//
							// Basic information
							//

							$title 		= esc_html__( 'Basic information', 'linear' );
							$sub_title 	= '';
							$content 	= map_value_labels( $listing, [
								'location' => [
									'value' => get_imploded_clean_array( $listing, ['address', 'gate'], ' ', ''), 
									'label' => esc_html__( 'Location', 'linear' )
								],
								'postalCode'            => esc_html__( 'Postal code', 'linear' ),
								'floorCount' => [
									'value' => has_value( $listing, 'floor' ) ? $listing['floor'] . ( has_value( $listing, 'floorCount') ? '/' . esc_html( $listing['floorCount'] ) : '' ) : '', 
									'label' => esc_html__( 'Floor', 'linear' )
								],
								'area'                  => esc_html__( 'Accommodation area', 'linear' ),
								'otherArea'             => [
									'value' => has_value( $listing, 'otherArea' ) && $listing['otherArea'] !== '0 m²' ? $listing['otherArea'] : '', 
									'label' => esc_html__( 'Other area', 'linear' )
								],
								'overallArea'           => esc_html__( 'Total area', 'linear' ),
								'areaMoreInfo'          => esc_html__( 'Area info', 'linear' ),
								'areaBasis' => [
									'value' => get_area_basis_value( $listing ), 
									'label' => esc_html__( 'Area basis', 'linear' )
								],
								'condition'             => esc_html__( 'Apartment condition', 'linear' ),
								'conditionInfo'         => esc_html__( 'Condition info', 'linear' ),
								'listingType'           => esc_html__( 'Listing type', 'linear' ),
								'rentalContractType'    => esc_html__( 'Rental contract type', 'linear' ),
								'constructionPhase'     => esc_html__( 'Construction phase', 'linear' ),
								'completeYear'          => esc_html__( 'Year of construction', 'linear' ),
								'deploymentYear'        => esc_html__( 'Year of deployment', 'linear' ),
								'constructionYearInfo'  => esc_html__( 'Construction year info', 'linear' ),
								'roomCount'             => esc_html__( 'Number of rooms', 'linear' ),
								'releaseDate'           => esc_html__( 'Release', 'linear' ),
								'freeOnText'            => esc_html__( 'Release info', 'linear' ),
								'ownershipType'         => esc_html__( 'Ownership type', 'linear' ),
								'newlyConstructed'      => esc_html__( 'Newly constructed', 'linear' ),
							]);
							$extra = false;
							if( has_value( $listing, 'floorPlans' ) ){
								if( isset( $listing['floorPlans'][0]['compressed'] ) && $listing['floorPlans'][0]['compressed'] ){
									$extra = '<div class="c-linear-floorplan"><img src="' . $listing['floorPlans'][0]['compressed'] . '" alt="' . $listing['floorPlans'][0]['description'] . '" /></div>';
								}
								
							}
							
							echo accordion( $title, $sub_title, $content, 'list', $extra );

							//
							// Price and cost
							//

							$title 		= esc_html__( 'Price and cost', 'linear' );
							$sub_title 	= '';
							$content 	= map_value_labels( $listing, [
								'formatted_debtFreePrice'   => esc_html__( 'Debt free price', 'linear' ),
								'selling_price'   			=> esc_html__( 'Selling price', 'linear' ),
								'formatted_debt'   			=> esc_html__( 'Debt', 'linear' ),
								'securityDeposit'   		=> esc_html__( 'Security deposit', 'linear' ),
								'squarePrice' => [
									'value' => has_values( $listing, ['area', 'debt', 'squarePrice']) ? $listing['squarePrice'] : '', 
									'label' => esc_html__( 'Price per square meter', 'linear' )
								],
								'maintenance_charge' => [
									'value' => (
										comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'SELL') &&
										!comparator_is_sub_value( $listing, ['rawDataForFiltering', 'listingType'], 'DETACHEDHOUSE')
									) ? get_maintenance_charge_value( $listing ) : '', 
									'label' => esc_html__( 'Maintenance charge', 'linear' )
								],
								'otherChargeDescription'   	=> esc_html__( 'Other charge description', 'linear' ),
								'laundryRoomCharge'         => __( 'Laundry room charge', 'linear' ),
								'waterCharge'               => __( 'Water charge', 'linear' ),
								'electricHeatingPowerUsage' => __( 'Electric heating power usage', 'linear' ),
								'electricHeatingCharge'     => __( 'Electric heating charge', 'linear' ),
								'saunaCharge'               => __( 'Sauna charge', 'linear' ),
								'parkingCharge' => [
									'value' => get_object_value( $listing, 'formatted_parkingCharge', (has_value( $listing, 'parkingCharge') ? $listing['parkingCharge'] : '') ), 
									'label' => esc_html__( 'Parking charge', 'linear' )
								],
								'heatingCharge'             => __( 'Heating charge', 'linear' ),
								'nonElectricHeatingCharge'  => __( 'Non-electric heating charge', 'linear' ),
								'averageTotalHeatingCharge' => __( 'Average total heating charge', 'linear' ),
								'roadMaintenanceCharge'     => __( 'Road maintenance charge', 'linear' ),
								'broadbandCharge'           => __( 'Broadband charge', 'linear' ),
								'waterAndSewageCharge'      => __( 'Water and sewage charge', 'linear' ),
								'sanitationCharge'          => __( 'Sanitation charge', 'linear' ),
								'satelliteCableTVCharge'    => __( 'Satellite cable TV charge', 'linear' ),
								'propertyTax'               => __( 'Property tax', 'linear' ),
								'chargeInfo'                => __( 'Additional charge info', 'linear' ),
							]);

							echo accordion( $title, $sub_title, $content, 'list' );

							//
							// Accommodation and materials
							//

							$title 		= esc_html__( 'Accommodation and materials', 'linear' );
							$sub_title 	= '';
							$content 	= map_value_labels( $listing, [
								'kitchenDescription'       => __( 'Kitchen description', 'linear' ),
								'kitchenEquipment'         => __( 'Kitchen equipment', 'linear' ),
								'stoveType'                => __( 'Stove', 'linear' ),
								'kitchenFloorMaterial'     => __( 'Kitchen floor material', 'linear' ),
								'kitchenWallMaterial'      => __( 'Kitchen wall material', 'linear' ),
								'livingRoomDescription'    => __( 'Living room', 'linear' ),
								'livingRoomWallMaterial'   => __( 'Living room wall material', 'linear' ),
								'livingRoomFloorMaterial'  => __( 'Living room floor material', 'linear' ),
								'bedroomDescription'       => __( 'Bedroom', 'linear' ),
								'bedroomWallMaterial'      => __( 'Bedroom wall material', 'linear' ),
								'bedroomFloorMaterial'     => __( 'Bedroom floor material', 'linear' ),
								'bedroomEquipment'         => __( 'Bedroom equipment', 'linear' ),
								'bathroomDescription'      => __( 'Bathroom', 'linear' ),
								'bathroomWallMaterial'     => __( 'Bathroom wall material', 'linear' ),
								'bathroomFloorMaterial'    => __( 'Bathroom floor material', 'linear' ),
								'bathroomEquipment'        => __( 'Basic bathroom equipment', 'linear' ),
								'bathroomEquipmentOther'   => __( 'Additional bathroom equipment', 'linear' ),
								'wcDescription'            => __( 'WC', 'linear' ),
								'wcWallMaterial'           => __( 'WC wall material', 'linear' ),
								'wcFloorMaterial'          => __( 'WC floor material', 'linear' ),
								'utilityRoomDescription'   => __( 'Utility room', 'linear' ),
								'utilityRoomWallMaterial'  => __( 'Utility room wall material', 'linear' ),
								'utilityRoomFloorMaterial' => __( 'Utility room floor material', 'linear' ),
								'utilityRoomEquipment'     => __( 'Utility room equipment', 'linear' ),
								'storageDescription'       => __( 'Storage description', 'linear' ),
								'sauna'                    => __( 'Sauna', 'linear' ),
								'saunaStoveType'           => __( 'Sauna stove type', 'linear' ),
								'saunaDescription'         => __( 'Sauna description', 'linear' ),
								'fireplace'                => __( 'Fireplace', 'linear' )
							]);

							echo accordion( $title, $sub_title, $content, 'list' );

							//
							// Additional information
							//

							$title 		= esc_html__( 'Additional information', 'linear' );
							$sub_title 	= '';
							$content 	= map_value_labels( $listing, [
								'yard'                			=> __( 'Yard', 'linear' ),
								'view'                			=> __( 'View', 'linear' ),
								'balcony'             			=> __( 'Balcony', 'linear' ),
								'balconyCompassPoint' 			=> __( 'Balcony orientation', 'linear' ),
								'terrace'             			=> __( 'Terrace', 'linear' ),
								'beach'               			=> __( 'Beach', 'linear' ),
								'beachType'           			=> __( 'Beach type', 'linear' ),
								'beachInfo'           			=> __( 'Beach info', 'linear' ),
								'ownBeachLine'        			=> __( 'Owned beach line', 'linear' ),
								'inIsland'            			=> __( 'On island', 'linear' ),
								'bodyOfWater'         			=> __( 'Body of water', 'linear' ),
								'otherSpacesInfo'     			=> __( 'Other spaces', 'linear' ),
								'propertyBuildings'     		=> __( 'Property buildings', 'linear' ),
								'hasCableTV'          			=> __( 'Cable TV', 'linear' ),
								'hasSatelliteAntenna' 			=> __( 'Satellite antenna', 'linear' ),
								'waterPipeInfo'       			=> __( 'Water pipes info', 'linear' ),
								'roadTo'              			=> __( 'Road to', 'linear' ),
								'restrictions'        			=> __( 'Restrictions', 'linear' ),
								'carPortCount'        			=> __( 'Car ports', 'linear' ),
								'garageCount'         			=> __( 'Garages and parking spaces count', 'linear' ),
								'hasParkingSpace'    			=> __( 'Has parking space', 'linear' ),
								'moreInfo'           			=> __( 'More info', 'linear' ),
								'parkingSpace'       			=> __( 'Parking space', 'linear' ),
								'parkingSpaceInfo'   			=> __( 'Parking space info', 'linear' ),
								'dealDoesNotInclude' 			=> __( 'Deal does not include', 'linear' ),
								'dealIncludes'       			=> __( 'Deal includes', 'linear' ),
								'petsAllowed' => [
									'value' => 
										comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'RENT')
									? 
										get_object_value( $listing, 'petsAllowed' ) 
									: 
										'', 
									'label' => esc_html__( 'Pets allowed', 'linear' )
								],
								'smokingAllowed' => [
									'value' => comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'RENT') ? get_object_value( $listing, 'smokingAllowed' ) : '', 
									'label' => esc_html__( 'Smoking allowed', 'linear' )
								]
							]);

							echo accordion( $title, $sub_title, $content, 'list' );

							//
							// Housing and real estate
							//

							$title = esc_html__( 'Housing and real estate', 'linear' );

							if( comparator_is_sub_value( $listing, ['rawDataForFiltering', 'type'], 'PROPERTY') ){
								$title = esc_html__( 'Real estate', 'linear' );
							} else {
								$title = esc_html__( 'Housing and real estate', 'linear' );
							}

							$sub_title 	= '';
							$content 	= map_value_labels( $listing, [
								'housingCooperativeName'             => __( 'Housing cooperative name', 'linear' ),
								'propertyManagerName'                => __( 'Property manager\'s name', 'linear' ),
								'propertyManagerOffice'              => __( 'Property manager\'s  office', 'linear' ),
								'propertyManagerPhone'               => __( 'Property manager\'s  phone', 'linear' ),
								'propertyManagerEmail'               => __( 'Property manager\'s  email', 'linear' ),
								'propertyManagerPostOffice'          => __( 'Property manager\'s  post office', 'linear' ),
								'propertyManagerCertificateDate'     => __( 'Property manager\'s  certificate date', 'linear' ),
								'propertyType'                       => __( 'Property type', 'linear' ),
								'propertyIdentifier'                 => __( 'Property identifier', 'linear' ),
								'propertyBuildingRights'             => __( 'Property buildings rights', 'linear' ),
								'propertyBuildingsInfo'              => __( 'Property buildings info', 'linear' ),
								'propertyBuildingRightsENumber'      => __( 'Building rights E number', 'linear' ),
								'propertyRestrictions'               => __( 'Property restrictions', 'linear' ),
								'propertyWaterPipe'                  => __( 'Property water pipe', 'linear' ),
								'propertySewer'                      => __( 'Property sewer', 'linear' ),
								'tenantIsResponsibleFor'             => __( 'Tenant is responsible for', 'linear' ),
								'sewerInfo'                          => __( 'Sewer info', 'linear' ),
								'propertyHasAntenna'                 => __( 'Property antenna', 'linear' ),
								'antennaSystem'                      => __( 'Antenna system', 'linear' ),
								'propertyMaintenance'                => __( 'Property maintenance by', 'linear' ),
								'propertyMaintenanceInfo'            => __( 'Property maintenance info', 'linear' ),
								'includes'                           => __( 'Property includes', 'linear' ),
								'zoningStatus'                       => __( 'Zoning status', 'linear' ),
								'zoningInfo'                         => __( 'Zoning Info', 'linear' ),
								'zoningDetails'                      => __( 'Zoning details', 'linear' ),
								'lotArea'                            => __( 'Lot area', 'linear' ),
								'lotOwnership'                       => __( 'Lot ownership', 'linear' ),
								'lotRentEndDate'                     => __( 'Lot rent date', 'linear' ),
								'lotType'                            => __( 'Lot type', 'linear' ),
								'lotInfo'                            => __( 'Lot info', 'linear' ),
								'landRenter'                         => __( 'Land renter', 'linear' ),
								'landYearRent' => [
									'value' => 
										comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'SELL') 
									? 
										get_object_value( $listing, 'landYearRent' ) 
									: 
										'', 
									'label' => esc_html__( 'Land yearly rent', 'linear' )
								],
								'housingTenure' => [
									'value' => comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'RENT') ? get_object_value( $listing, 'housingTenure' ) : '', 
									'label' => esc_html__( 'Housing tenure', 'linear' )
								],
								'roadTolls'                          => __( 'Road tolls', 'linear' ),
								'constructionMaterial'               => __( 'Construction material', 'linear' ),
								'otherConstructionMaterial'          => __( 'Other construction materials', 'linear' ),
								'constructionMaterialInfo'           => __( 'Construction material info', 'linear' ),
								'heatingSystem'                      => __( 'Heating system', 'linear' ),
								'ventilation'                        => __( 'Ventilation', 'linear' ),
								'roofCondition'                      => __( 'Roof condition', 'linear' ),
								'roofType'                           => __( 'Roof type', 'linear' ),
								'roofingMaterial'                    => __( 'Roofing material', 'linear' ),
								'housingCooperativeRedemptionRight' => [
									'value' => comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'SELL') ? get_object_value( $listing, 'housingCooperativeRedemptionRight' ) : '', 
									'label' => esc_html__( 'Housing cooperative redemption right', 'linear' )
								],
								'partnerRedemptionRight' => [
									'value' => comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'SELL') ? get_object_value( $listing, 'partnerRedemptionRight' ) : '', 
									'label' => esc_html__( 'Partner redemption right', 'linear' )
								],
								'listingHasEnergyCertificate' => [
									'value' => maybe_get_constant( get_object_value( $listing, 'listingHasEnergyCertificate', null ) ), 
									'label' => esc_html__( 'Energy certificate', 'linear' )
								],
								'energyClass'                        => __( 'Energy class', 'linear' ),
								'energyClassInfo'                    => __( 'Energy class info', 'linear' ),
								'electricityContract' => [
									'value' => maybe_get_constant( get_object_value( $listing, 'electricityContract', null ) ), 
									'label' => esc_html__( 'Electricity contract', 'linear' )
								],
								'electricPlanTransfers'              => __( 'Electric plan transfers', 'linear' ),
								'trafficConnections'                 => __( 'Traffic connections', 'linear' ),
								'trafficConnectionsInfo'             => __( 'Traffic info', 'linear' ),
								'localInfo'                          => __( 'Local information', 'linear' ),
								'services'                           => __( 'Services', 'linear' ),
								'schools'                            => __( 'Schools', 'linear' ),
								'kindergarten'                       => __( 'Kindergarten', 'linear' ),
								'electricPlugParkingSpaceCount'      => __( 'Parking spaces with electric plug', 'linear' ),
								'yardParkingSpaceCount'              => __( 'Parking yard spaces', 'linear' ),
								'parkingGarageCount'                 => __( 'Parking garage spaces', 'linear' ),
								'parkingInfo'                        => __( 'Parking info', 'linear' ),
								'telecommunicationsSystem'           => __( 'Telecommunications system', 'linear' ),
								'housingCooperativeParkingSpaces'    => __( 'Housing cooperative parking spaces', 'linear' ),
								'housingCooperativeSauna'        	 => __( 'Housing cooperative sauna', 'linear' ),
								'housingCooperativeHas'              => __( 'Cooperative housing', 'linear' ),
								'housingCooperativeElevator'         => __( 'Housing cooperative elevator', 'linear' ),
								'housingCooperativeRetailSpaceArea'             => [
									'value' => has_value( $listing, 'housingCooperativeRetailSpaceArea' ) && $listing['housingCooperativeRetailSpaceArea'] !== '0 m²' ? $listing['housingCooperativeRetailSpaceArea'] : '', 
									'label' => esc_html__( 'Housing cooperative retail space area', 'linear' )
								],
								'housingCooperativeRetailSpaceCount' => __( 'Housing cooperative retail space count', 'linear' ),
								'housingCooperativeApartmentCount'   => __( 'Housing cooperative apartment count', 'linear' ),
								'housingCooperativeRevenue' => [
									'value' => (
										comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'SELL') &&
										!in_array( get_object_value( $listing, 'housingCooperativeRevenue', '' ), ["0", "0 €", "0 €"] )
									) ? get_object_value( $listing, 'housingCooperativeRevenue', '' ) : '', 
									'label' => esc_html__( 'Housing cooperative revenue', 'linear' )
								],
								'housingCooperativeAdditionalInfo'   => __( 'Housing cooperative additional info', 'linear' ),
								'conditionInvestigationDate'         => __( 'Condition inspection date', 'linear' ),
								'asbestosSurvey'		             => __( 'Asbestos survey', 'linear' ),
								'asbestosSurveyInfo'	             => __( 'Asbestos survey info', 'linear' ),
								'humidityInvestigationDate'          => __( 'Humidity inspection date', 'linear' )
							]);

							echo accordion( $title, $sub_title, $content, 'list' );


							//
							// Past renovations
							//

							$title 		= esc_html__( 'Past renovations', 'linear' );
							$sub_title 	= '';
							$content 	= has_value( $listing, 'pastRenovations' ) ? [$listing['pastRenovations']] : [];
							
							echo accordion( $title, $sub_title, $content, 'simple' );

							//
							// Upcoming renovations
							//

							$title 		= esc_html__( 'Upcoming renovations', 'linear' );
							$sub_title 	= '';
							$content 	= has_value( $listing, 'housingCooperativeUpcomingRenovations' ) ? [$listing['housingCooperativeUpcomingRenovations']] : [];
							
							echo accordion( $title, $sub_title, $content, 'simple' );

						?>
					</div>
				</div>
				
			</div>
		</div>
	</div>

	<?php

}
