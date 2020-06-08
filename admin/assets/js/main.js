(function ($) {
	$(document).ready(() => {
		let counter = $(".mcqs>*").length;
		$("span#meng_mcqs_add_btn").on("click", (e) => {
			counter++;
			let html = `<div>${counter}</div>`;
			html += `<input name="meng_mcqs[${counter}][statement]" class="mcqs_statement" placeholder="Enter the mcq's statement" />`;
			html += `<input name="meng_mcqs[${counter}][options]" class="mcqs_options" placeholder="Enter mcqs options here separated by '|'" />`;
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
	});
})(jQuery);
