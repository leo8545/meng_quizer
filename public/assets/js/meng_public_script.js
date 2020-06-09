(function ($) {
	$(document).ready(function () {
		$("#mcqs_form").submit((e) => {
			e.preventDefault();
			$.ajax({
				data: {
					action: "my_action",
					security: ajaxObject.security,
					serialized: $("#mcqs_form").serialize(),
					exId: $("#ex_id").val(),
				},
				method: "POST",
				url: ajaxObject.ajax_url,
			}).success((_response) => {
				response = JSON.parse(_response);
				console.log(response);
				// check answers
				correct_answers = 0;
				for (i = 1; i <= Object.keys(response).length; i++) {
					userAnswer = $(`div.mcq-${i} input[type=radio]:checked`).val();
					console.log("u:" + userAnswer + "a:" + response[i]);
					if (userAnswer !== undefined && userAnswer == response[i]) {
						correct_answers++;
						answer = { correct: true, message: "Correct answer" };
					} else {
						answer = { correct: false, message: "Incorrect answer" };
					}

					if ($(`div.mcq-${i}`).find("div.answer").length !== 0) {
						if (answer.correct) {
							$(`div.mcq-${i} div.answer`).removeClass("incorrect");
							$(`div.mcq-${i} div.answer`).addClass("correct");
						} else {
							$(`div.mcq-${i} div.answer`).removeClass("correct");
							$(`div.mcq-${i} div.answer`).addClass("incorrect");
						}
						$(`div.mcq-${i} div.answer`).text(answer.message);
					} else {
						$(`div.mcq-${i}`).append(
							`<div class='answer ${
								answer.correct ? "correct" : "incorrect"
							}'>${answer.message}</div>`
						);
					}
				}
				$("#meng_mcqs_result").text(
					`Your result: ${
						Math.round(
							((correct_answers / Object.keys(response).length) * 100 +
								Number.EPSILON) *
								100
						) / 100
					}%`
				);
			});
		});
		$("ul.meng_sortables").sortable({
			start: function (event, ui) {
				$(ui.item).addClass("meng-sortable-active");
			},
			stop: function (event, ui) {
				$(ui.item).removeClass("meng-sortable-active");
			},
		});
		$("ul.meng_sortables").disableSelection();
		var c = 0;
		$(".meng_toggle_sibling").on("click", (e) => {
			c++;
			if (c % 2 === 0) {
				$(".meng_toggle_sibling").text("show answers");
			} else {
				$(".meng_toggle_sibling").text("hide answers");
			}
			$(".meng_toggle_sibling").siblings().toggle();
		});
		// MCQs Cloze
		var clozeResult = {};
		// check if a selector has been loaded
		var waitForEl = function (selector, callback) {
			if (jQuery(selector).length) {
				callback();
			} else {
				setTimeout(function () {
					waitForEl(selector, callback);
				}, 100);
			}
		};
		waitForEl($(".meng_mcqs_cloze_container"), function () {
			var postId = $(".meng_mcqs_cloze_container").data("excercise");
			var postData = { postId };
			$.ajax({
				data: {
					action: "action_meng_cloze",
					security: ajaxObject.security,
					postData,
				},
				method: "POST",
				url: ajaxObject.ajax_url,
			}).success((_response) => {
				clozeResult = JSON.parse(_response);
			});
		});
		$(".meng-cloze-row").on("click", (e) => {
			var choice = e.target.textContent.trim();
			var choiceRow = e.target.parentNode.getAttribute("data-option");
			if (clozeResult[choiceRow].options.correct === choice) {
				e.target.classList.add("green");
			} else {
				e.target.classList.add("red");
			}
		});
		// Meng Cloze Tabs
		// console.log($(".meng_tabs_wrapper .meng_cloze_tab_content"));
		waitForEl($(".meng_tabs_wrapper"), () => {
			$(".meng_tabs_wrapper .meng_cloze_tab_content")[0].classList.add(
				"meng-active-tab-content"
			);
			$(".meng_tabs_headings .meng-tab-heading")[0].classList.add(
				"meng-active-tab"
			);
		});
		$(".meng-tab-heading").on("click", (e) => {
			var parent = e.target.closest(".meng_tabs_headings");
			$.each(parent.children, (i, v) => {
				v.classList.remove("meng-active-tab");
			});
			e.target.classList.add("meng-active-tab");
			var option = e.target.getAttribute("data-option");
			$.each(parent.parentNode.children, (i, v) => {
				v.classList.remove("meng-active-tab-content");
				var contentId = v.getAttribute("data-option");
				if (contentId == option) {
					v.classList.add("meng-active-tab-content");
				}
				console.log(contentId);
			});
		});
		// Blanks basic form ajax
		waitForEl($(".meng_blanks_basic_form"), (e) => {
			var form = $(".meng_blanks_basic_form");
			var postId = form.find("#ex_id").val();
			var result = {};
			$.ajax({
				data: {
					action: "action_meng_blanks_basic",
					security: ajaxObject.security,
					postId,
				},
				method: "POST",
				url: ajaxObject.ajax_url,
			})
				.success((_response) => {
					var response = JSON.parse(_response);
					$.each(response, (i, v) => {
						result[parseInt(i)] = v.correct;
					});
					console.log(result);
				})
				.fail(function (jqXHR, exception) {
					console.log(exception);
				});
			form.submit((e) => {
				e.preventDefault();
				var inputs = form.find("input.meng_blanks_basic_input");
				var correctCounter = 0;
				$.each(inputs, (i, v) => {
					var id = parseInt(v.getAttribute("data-id"));
					if (v.value.toLowerCase().trim() == result[id].toLowerCase().trim()) {
						correctCounter++;
						var rightInput = $(`input.meng_blanks_basic_input[data-id=${id}]`);
						var _result = rightInput
							.closest("td")
							.find("div.meng_blanks_basic_result");
						if (_result.length === 0) {
							rightInput
								.closest("td")
								.append(
									`<div class="meng_blanks_basic_result">correct answer</div>`
								);
						} else {
							rightInput
								.closest("td")
								.find("div.meng_blanks_basic_result")
								.text("correct answer");
						}
					} else {
						var wrongInput = $(
							`input.meng_blanks_basic_input[data-id="${i + 1}"`
						);
						var _result = wrongInput
							.closest("td")
							.find("div.meng_blanks_basic_result");
						if (_result.length === 0) {
							wrongInput
								.closest("td")
								.append(
									'<div class="meng_blanks_basic_result">wrong answer</div>'
								);
						} else {
							wrongInput
								.closest("td")
								.find("div.meng_blanks_basic_result")
								.text("wrong answer");
						}
					}
				});
				if (
					$(".meng_blanks_basic_container").find(
						".meng_blanks_basic_final_result"
					).length !== 0
				) {
					$(".meng_blanks_basic_final_result").text(
						`Your result is ${
							inputs.length
								? (parseInt(correctCounter) / inputs.length) * 100
								: 0
						}%`
					);
				} else {
					$(".meng_blanks_basic_container").append(
						`<div class="meng_blanks_basic_final_result">Your result is ${
							correctCounter
								? (parseInt(correctCounter) / inputs.length) * 100
								: 0
						}%</div>`
					);
				}
			});
		});
	});
})(jQuery);
