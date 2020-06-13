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
		var meng_blanks_cols_result = {};
		waitForEl("form.meng_blanks_cols_form", (e) => {
			var postId = parseInt($("input#ex_id").val());
			$.ajax({
				data: {
					action: "action_meng_blanks_cols",
					security: ajaxObject.security,
					postId,
				},
				method: "post",
				url: ajaxObject.ajax_url,
			}).success((_response) => {
				meng_blanks_cols_result = JSON.parse(_response);
			});
		});
		$("form.meng_blanks_cols_form").submit((e) => {
			e.preventDefault();
			var inputs = $("input.meng_blanks_cols_input");
			var correctCounter = 0;
			$.each(inputs, (i, v) => {
				var field_id = parseInt(v.getAttribute("data-field_id"));
				var option_id = parseInt(v.getAttribute("data-option_id"));
				if (
					meng_blanks_cols_result.fields[field_id].options_input[option_id] ===
					v.value.toLowerCase().trim()
				) {
					correctCounter++;
					v.classList.remove("meng-wrong-answer");
					v.classList.add("meng-correct-answer");
				} else {
					v.classList.remove("meng-correct-answer");
					v.classList.add("meng-wrong-answer");
				}
			});
			var _result = (correctCounter / inputs.length) * 100;
			var _result_html = `Your result is: ${_result}%`;
			var parent = $("form.meng_blanks_cols_form").closest(".meng-blanks-cols");
			if (parent.find(".meng_result").length === 0) {
				parent.append(`<div class="meng_result">${_result_html}</div>`);
			} else {
				parent.find(".meng_result").text(_result_html);
			}
		});
		var counterToggle = 0;
		$(".meng-show-answers-btn").on("click", (e) => {
			counterToggle++;
			var answersWrapper = $(".meng-show-answers-btn").closest(
				".meng-answers-wrapper"
			);
			if (counterToggle % 2 !== 0) {
				answersWrapper.find(".meng-answers-table").show();
				e.target.textContent = "Hide answers";
			} else {
				answersWrapper.find(".meng-answers-table").hide();
				e.target.textContent = "Show answers";
			}
		});
		/**
		 * Quiz type: meng_multi_selectors
		 *
		 * @since 1.0.0
		 */
		waitForEl(".meng-multi-selector-wrapper", (e) => {
			var response = {};
			// Hide everything and adds loading div
			$(".meng-multi-selector-wrapper>*:not(.meng-loading)").hide();
			$(".meng-multi-selector-wrapper").prepend(
				"<div class='meng-loading'>Loading...</div>"
			);
			$.ajax({
				data: {
					action: "action_meng_multi_selector",
					postId: $(".meng-form #ex_id").val(),
					security: ajaxObject.security,
				},
				url: ajaxObject.ajax_url,
				method: "post",
			})
				.success((_response) => {
					response = JSON.parse(_response);
					if (response) {
						// Removes loading and show everything
						$(".meng-loading").remove();
						$(".meng-multi-selector-wrapper>*").show();
					} else {
						$(".meng-loading").text("Something went wrong!");
					}
				})
				.error((err) => {
					$(".meng-loading").text("Server error");
				});
			var meng_multi_selector_inputs = $(".meng-form input[type=checkbox]");
			var userCorrectAnswers = [];
			meng_multi_selector_inputs.on("click", (e) => {
				var qid = e.target.getAttribute("data-qid");
				var optionId = e.target.getAttribute("data-option_id");
				var label = e.target.parentNode;
				if (response) {
					if (response[qid].options.correct[optionId] === e.target.value) {
						label.classList.add("meng-msel-correct-answer");
						// userCorrectAnswers[qid] = e.target.value;
						// userCorrectAnswers++;
						userCorrectAnswers.push(qid + optionId);
					} else {
						label.classList.add("meng-msel-wrong-answer");
					}
				}
				if (!e.target.checked) {
					var i = userCorrectAnswers.indexOf(qid + optionId);
					if (i > -1) {
						userCorrectAnswers.splice(i);
					}
					label.classList.remove(
						"meng-msel-correct-answer",
						"meng-msel-wrong-answer"
					);
				}
			});
			$(".meng-form").submit((e) => {
				e.preventDefault();
				var totalCorrectAnswers = 0;
				if (response) {
					$.each(response, (i, q) => {
						totalCorrectAnswers += Object.keys(q.options.correct).length;
					});
					var result = userCorrectAnswers
						? (userCorrectAnswers.length / totalCorrectAnswers) * 100
						: 0;
					const resultWrapper = $(".meng-multi-selector-wrapper .meng-result");
					var resultText = `Your result is ${result}%`;
					if (resultWrapper.length) {
						resultWrapper.text(resultText);
					} else {
						$(".meng-multi-selector-wrapper").append(
							`<div class="meng-result">${resultText}</div>`
						);
					}
				}
			});
		});
		/**
		 * Quiz type: meng_true_false
		 *
		 * @since 1.0.0
		 */
		waitForEl(".meng-true-false-wrapper", (e) => {
			var response = {};
			// Hide everything and adds loading div
			$(".meng-true-false-wrapper>*:not(.meng-loading)").hide();
			$(".meng-true-false-wrapper").prepend(
				"<div class='meng-loading'>Loading...</div>"
			);
			$.ajax({
				data: {
					action: "action_meng_true_false",
					security: ajaxObject.security,
					postId: $("#ex_id").val(),
				},
				url: ajaxObject.ajax_url,
				method: "post",
			}).success((_response) => {
				response = JSON.parse(_response);
				if (response) {
					// Removes loading and show everything
					$(".meng-loading").remove();
					$(".meng-true-false-wrapper>*").show();
				} else {
					$(".meng-loading").text("Something went wrong!");
				}
			});
			var userCorrectAnswers = new Set();
			$(".meng-form").submit((e) => {
				e.preventDefault();
				var inputs = $(".meng_true_false_input");
				$.each(inputs, (index, input) => {
					if (input.checked) {
						var li = input.parentNode.parentNode;
						var qid = parseInt(li.getAttribute("data-qid"));
						if (response) {
							if (parseInt(response[qid].answer) === parseInt(input.value)) {
								userCorrectAnswers.add(qid);
							} else {
								userCorrectAnswers.delete(qid);
							}
						}
					}
				});
				var result = response
					? (userCorrectAnswers.size / Object.keys(response).length) * 100
					: 0;
				result = Math.round((result + Number.EPSILON) * 100) / 100;
				var resultText = `Your result is: ${result}%`;
				var resultWrapper = $(".meng-true-false-wrapper .meng-result");
				if (resultWrapper.length) {
					resultWrapper.text(resultText);
				} else {
					$(".meng-true-false-wrapper").append(
						`<div class="meng-result">${resultText}</div>`
					);
				}
			});
		});
	});
})(jQuery);
