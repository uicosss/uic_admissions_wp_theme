<?php

$section_anchor = preg_replace('/[^a-zA-Z0-9]/', '-', strtolower(get_field('section_anchor')));;
$header_text = get_field('header_text');

$calculator_data = get_remote_tuition_data();

?>

<script>
	// window.__UIC_CALCULATOR_DATA__ = <?= json_encode($calculator_data) ?>;
	window.__UIC_CALCULATOR_DATA__ = <?php echo json_encode($calculator_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

	if (window.__UIC_CALCULATOR_DATA__ === null) {
		window.__UIC_CALCULATOR_DATA__ = {};
		console.warn('UIC Calculator: Failed to load remote tuition data.');
	}
</script>


<section class="uic-calculator uic-section" tabindex="-1" id="<?= !empty($section_anchor) ? $section_anchor : 'calculator' ?>">
	<div class="uic-section__container">
		<div class="uic-section__inner">
			<form class="uic-calculator__questionnaire-container">
				<?php if (!empty($header_text)) { ?>
					<h3 class="uic-h3"><?= $header_text ?></h3>
				<?php } ?>
				<div class="uic-calculator__questionnaire">
					<label
						class="uic-calculator__prompt"
						for="uic-calculator__select--location"
						id="uic-calculator__select-label--location">
						Where do you live?<span title="required">*</span>
					</label>
					<div class="uic-calculator__response">
						<select
							data-trigger
							required
							aria-required="true"
							aria-labelledby="uic-calculator__select-label--location"
							class="form-control uic-calculator__select"
							name="location"
							id="uic-calculator__select--location">
							<option value="">Select A Location</option>
							<option value="resident">Resident / In State</option>
							<option value="non-resident">Non Resident / Out of State</option>
							<option value="international">International</option>
						</select>
					</div>
					<label
						class="uic-calculator__prompt"
						for="uic-calculator__select--program"
						id="uic-calculator__select-label--program">
						What program are you interested in?<span title="required">*</span>
					</label>
					<div class="uic-calculator__response">
						<select
							data-trigger
							required
							aria-required="true"
							aria-labelledby="uic-calculator__select-label--program"
							class="form-control uic-calculator__select"
							name="program"
							id="uic-calculator__select--program">
							<option value="">Select A Program</option>
							<?php
							foreach ($calculator_data['differentials'] as $differential) {
								echo '<optgroup label="' . esc_attr($differential['department']) . '">';
								foreach ($differential['programs'] as $program) {
									echo '<option value="' . $program['cost'] . '">' . $program['name'] . '</option>';
								}
								echo '</optgroup>';
							}
							?>
						</select>
					</div>
					<label
						class="uic-calculator__prompt"
						for="uic-calculator__select--housing"
						id="uic-calculator__select-label--housing">
						Will you live on-campus?<span title="required">*</span>
					</label>
					<div class="uic-calculator__response">
						<select
							data-trigger
							required
							aria-required="true"
							aria-labelledby="uic-calculator__select-label--housing"
							class="form-control uic-calculator__select"
							name="housing"
							id="uic-calculator__select--housing">
							<option value="">Select Housing Needs</option>
							<option value="on-campus">On-Campus</option>
							<option value="off-campus">Off-Campus</option>
						</select>
					</div>
					<div class="uic-calculator__prompt uic-calculator__submit-label--hidden uic-calculator__submit-label">You're finished!</div>
					<div class="uic-calculator__response uic-calculator__submit-container">
						<button type="submit" disabled class="uic-calculator__submit">Calculate Your Tuition</button>
					</div>
				</div>
			</form>
			<div class="uic-calculator__results uic-calculator__results">
				<button type="button" class="uic-calculator__results-close" title="Close results">
					<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64">
						<use class="uic-calculator__results-close--default" xlink:href="<?= THEME_ASSET_BASE ?>/images/calculator-results-close.svg#uic-calculator__results-close" />
						<use class="uic-calculator__results-close--hover" xlink:href="<?= THEME_ASSET_BASE ?>/images/calculator-results-close.svg#uic-calculator__results-close--hover" />
					</svg>
					<span class="sr-only">Close results</span>
				</button>
				<h3 class="uic-h3">Here's your annual estimated cost of attendance:</h3>
				<div class="uic-calculator__breakdown">
					<div class="uic-calculator__line-item__choices-desc"></div>
					<div class="uic-calculator__line-item__tuition-label">
						Tuition
					</div>
					<div class="uic-calculator__line-item__tuition-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__tuition-value">
						0
					</div>
					<div class="uic-calculator__line-item__divider-0"></div>
					<div class="uic-calculator__line-item__differential-label">
						Program Differential
					</div>
					<div class="uic-calculator__line-item__differential-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__differential-value">
						0
					</div>
					<div class="uic-calculator__line-item__divider-1"></div>
					<div class="uic-calculator__line-item__housing-estimate-label">
						Housing and Food Expenses
					</div>
					<div class="uic-calculator__line-item__housing-estimate-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__housing-estimate-value">
						0
					</div>
					<div class="uic-calculator__line-item__fees-label">
						Mandatory Fees
					</div>
					<div class="uic-calculator__line-item__fees-desc">
						Student fees, health insurance, assessments
					</div>
					<div class="uic-calculator__line-item__fees-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__fees-value">
						0
					</div>
					<div class="uic-calculator__line-item__divider-2"></div>
					<div class="uic-calculator__line-item__attendance-label">
						Estimated Costs Billed by the University
					</div>
					<div class="uic-calculator__line-item__attendance-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__attendance-value">
						0
					</div>
					<div class="uic-calculator__line-item__expenses-label">
						Estimated Variable Expenses
					</div>
					<div class="uic-calculator__line-item__books-label">
						Books and Supplies
					</div>
					<div class="uic-calculator__line-item__books-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__books-value">
						0
					</div>
					<div class="uic-calculator__line-item__housing-variable-label">
						Housing and Food Expenses
					</div>
					<div class="uic-calculator__line-item__housing-variable-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__housing-variable-value">
						0
					</div>
					<div class="uic-calculator__line-item__personal-label">
						Personal Expenses
					</div>
					<div class="uic-calculator__line-item__personal-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__personal-value">
						0
					</div>
					<div class="uic-calculator__line-item__transportation-label">
						Transportation
					</div>
					<div class="uic-calculator__line-item__transportation-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__transportation-value">
						0
					</div>
					<div class="uic-calculator__line-item__divider-3 "></div>
					<div class="uic-calculator__line-item__variable-label">
						Total Variable Expenses
					</div>
					<div class="uic-calculator__line-item__variable-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__variable-value">
						0
					</div>
					<div class="uic-calculator__line-item__divider-4 "></div>
					<div class="uic-calculator__line-item__total-label">
						Total Estimated Cost of Attendance
					</div>
					<div class="uic-calculator__line-item__total-symbol">
						$
					</div>
					<div class="uic-calculator__line-item__total-value">
						0
					</div>
					<div class="uic-calculator__line-item__divider-5 "></div>
				</div>
				<div class="uic-calculator__results__learn-more-container">
					<a href="https://admissions.uic.edu/undergraduate/tuition-financial-aid" target="_blank" title="Learn more about tuition from the UIC Registrar" class="uic-calculator__results__learn-more">Learn More</a>
				</div>
				<div class="uic-calculator__results__links">
				</div>
				<div class="uic-calculator__footer">
					<p>These estimates are for on-campus, full-time, undergraduate students. The exact cost depends on your residency status, your major of study, whether you choose to live on campus, and other variable expenses. Visit the registrars website for detailed explanations and other student types.</p>
					<p><span>Tuition:</span> Billed.</p>
					<p><span>Program Differential:</span> Billed. Some programs assess a differential to cover additional costs of instruction.</p>
					<p><span>Fees and Assessments:</span> Billed. Support a range of student support services, building and technology at UIC. Health insurance can be waived if you have existing and equivalent coverage.</p>
					<p><span>Books and Supplies:</span> Estimate. Expenses may vary depending on the courses you are taking.</p>
					<p><span>Housing and Food:</span> Estimate. UIC students are not required to live on campus, but rates range based on room selection and meal plan.</p>
					<p><span>Personal Expenses:</span> Estimate.</p>
					<p><span>Transportation:</span> Estimate.</p>
				</div>
			</div>
		</div>
	</div>
</section>