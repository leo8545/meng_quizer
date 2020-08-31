(function ($) {
	$(document).ready(() => {
		let counter = $(".mcqs>*").length;
		$("span#meng_mcqs_add_btn").on("click", (e) => {
			counter++;
			let html = `<div id="mcq-${counter}" class="meng_quiz_single_field">
					<div>${counter}.</div>
					<label for="">Statment for the mcqs:</label>
					<input name="meng_mcqs[${counter}][statement]" value="" class="mcqs_statement" />
					<label for="">Options for the mcqs:</label>
					<input name="meng_mcqs[${counter}][options]" value="" class="mcqs_options" />
				</div>`;
			$(".mcqs").append(`<div id="mcq-${counter}">${html}</div>`);
		});
		var counter1 = $(".meng_sortables>*").length;
		$("span#meng_sortable_add_btn").on("click", (e) => {
			counter1++;
			var html = `<div class="sortables_field_wrapper meng_quiz_single_field">
							<div class="meng_counter">${counter1}</div>
							<input type="text" name="meng_sortables[${counter1}][static]" class="meng_sortables_static_input" value="">
							<input type="text" name="meng_sortables[${counter1}][dynamic]" class="meng_sortables_dynamic_input" value="" placeholder="Enter value for draggable field...">
						</div>`;
			$(".meng_sortables").append(html);
		});
		var counter2 = $(".meng-blanks>*").length;
		$("span#meng_blanks_add_btn").on("click", (e) => {
			counter2++;
			var html = `<div class="blanks_basic_wrapper meng_quiz_single_field">
								<div class="meng_counter">${counter2}</div>
								<label>Statement:</label>
								<input type="text" name="meng_blanks_basic[${counter2}][statement]" class="meng_blanks_basic_statement" value="">
							</div>`;
			$(".meng-blanks").append(html);
		});
		$("#meng-blanks-cols-names-btn").on("click", (e) => {
			var count = $("#meng-blanks-count-cols").val();
			if (count >= 2 && count <= 4) {
				for (var j = 1; j <= count; j++) {
					if (
						$(".meng-blanks-cols-names").find(`#meng-blanks-col-${j}`)
							.length === 0
					) {
						var html = `<div id="meng-blanks-col-${j}">
											<label>Label for column ${j}:</label>
											<input type="text" required name="meng_blanks_cols[cols][names][${j}]" />
										</div>`;
						$(".meng-blanks-cols-names").append(
							`<div id="meng-blanks-col-${j}">${html}</div>`
						);
					}
					if (count < $(".meng-blanks-cols-names>*").length) {
						var len = $(".meng-blanks-cols-names>*").length;
						for (var k = len; k > count; k--) {
							$(`.meng-blanks-cols-names #meng-blanks-col-${k}`).remove();
						}
					}
				}
			} else {
				alert(
					`Possible values of no. of columns can be between 2 and 4. You entered: ${count}`
				);
			}
		});
		var counter3 = $(".blanks-cols-fields>*").length;
		$("#meng_blanks_cols_add_btn").on("click", (e) => {
			counter3++;
			var html = `<div class="blanks_cols_field_wrapper meng_quiz_single_field">
								<div class="meng_counter">${counter3}</div>
								<label>Enter the options:</label>
								<input type="text" name="meng_blanks_cols[fields][${counter3}]" class="meng_blanks_cols_options" value="" />
							</div>`;
			$(".blanks-cols-fields").append(html);
		});
		$("body.post-type-meng_blanks_cols form#post").submit((e) => {
			var blanks_cols_fields = $(".meng_blanks_cols_options");
			var count = $("#meng-blanks-count-cols").val();
			$.each(blanks_cols_fields, (i, v) => {
				var input = $(`input[name="meng_blanks_cols[fields][${i + 1}]"]`);
				if (v.value.replace(/[^\|]/g, "").length !== count - 1) {
					e.preventDefault();
					input.css({
						border: "1px solid #f00",
					});
					input.focus();
					input
						.closest(".meng_quiz_single_field")
						.append(
							`<div class="meng_admin_error">Please add atleast ${count} fields.</div>`
						);
				} else {
					input.css({
						border: "unset",
					});
					input
						.closest(".meng_quiz_single_field")
						.find(".meng_admin_error")
						.remove();
				}
			});
		});
		var counter4 = $(".meng-questions>*").length;
		$("#meng_multi_selector_add_btn").on("click", (e) => {
			counter4++;
			var html = `<div class="meng_quiz_single_field">
								<div class="meng_counter"><strong>Question: ${counter4}</strong></div>
								<div class="meng-form-field">
									<label for="meng_multi_selector_statement-${counter4}">Statement</label>
									<input type="text" name="meng_multi_selector[${counter4}][statement]" id="meng_multi_selector_statement-${counter4}" value="">
								</div>
								<div class="meng-form-field">
									<label for="meng_multi_selector_options-${counter4}">Options</label>
									<input type="text" name="meng_multi_selector[${counter4}][options]" id="meng_multi_selector_options-${counter4}" value="">
								</div>
							</div>`;
			$(".meng-questions").append(html);
		});
		var counter5 = $(".meng-questions>*").length;
		$("span#meng_true_false_add_btn").on("click", (e) => {
			counter5++;
			var html = `<div class="meng_quiz_single_field">
								<div class="meng_counter"><strong>${counter5}</strong></div>
								<input type="text" name="meng_true_false[${counter5}][statement]" value="" />
								<label><input type="radio" name="meng_true_false[${counter5}][answer]" value="1" />True</label>
								<label><input type="radio" name="meng_true_false[${counter5}][answer]" value="0" />False</label>
							</div>`;
			$(".meng-questions").append(html);
		});
	});
})(jQuery);
