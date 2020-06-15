(function ($) {
	class MengAdmin {
		constructor({ wrapper }) {
			this.wrapper = wrapper;
			this.showLoading();
			this.response = null;
			this.result = null;
		}
		async request({ action, postId }) {
			const { ajax_url, security } = ajaxObject;
			return new Promise((resolve, reject) => {
				var response = {};
				$.ajax({
					data: {
						action,
						security,
						postId,
					},
					url: ajax_url,
					method: "post",
				})
					.success((_response) => {
						response = JSON.parse(_response);
						this.response = response;
						this.hideLoading();
						resolve(response);
					})
					.error((err) => {
						reject(err);
					});
			});
		}
		/**
		 * Show loading div on page load,
		 */
		showLoading() {
			$(`${this.wrapper}>*:not(.meng-loading)`).hide();
			$(`${this.wrapper}`).prepend(
				`<div class='meng-loading'><img src='${ajaxObject.plugin_url}/public/assets/images/loading-2.gif' class="meng-loading-image">Loading...</div>`
			);
		}
		/**
		 * Hide loading div when response arrives
		 */
		hideLoading() {
			$(".meng-loading").remove();
			$(`${this.wrapper}>*`).show();
		}
		/**
		 * Get percentage of ratio
		 * @param integer dividend
		 * @param integer divisor
		 */
		getResult(dividend, divisor) {
			this.result = this.response
				? Math.round(((dividend / divisor) * 100 + Number.EPSILON) * 100) / 100
				: 0;
			return this.result;
		}
		/**
		 * Show result div
		 * @param integer dividend
		 * @param integer divisor
		 */
		showResult({ dividend, divisor }) {
			var result = this.getResult(dividend, divisor);
			var resultText = result === 100 ? "😃 Great Job! " : "";
			resultText += `Your result is ${result}%`;
			var resultWrapper = $(`${this.wrapper} .meng-result`);
			if (resultWrapper.length) {
				resultWrapper.text(resultText);
			} else {
				$(`${this.wrapper}`).append(
					`<div class="meng-result">${resultText}</div>`
				);
			}
		}
		/**
		 * Show answers div
		 */
		showAnswers({
			toggler = ".meng-show-answers-btn",
			toggleTarget = ".meng-answers-table",
		} = {}) {
			var i = 0;
			$(toggler).on("click", (e) => {
				i++;
				var answersWrapper = $(toggler).closest(".meng-answers-wrapper");
				if (i % 2 !== 0) {
					answersWrapper.find(toggleTarget).show();
					e.target.textContent = "Hide answers";
				} else {
					answersWrapper.find(toggleTarget).hide();
					e.target.textContent = "Show answers";
				}
			});
		}
	}
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
	$(document).ready(function () {
		$("ul.meng_sortables").sortable({
			start: function (event, ui) {
				$(ui.item).addClass("meng-sortable-active");
			},
			stop: function (event, ui) {
				$(ui.item).removeClass("meng-sortable-active");
			},
		});
		$("ul.meng_sortables").disableSelection();
		/**
		 * Quiz type: meng_mcqs_basic
		 *
		 * @since 1.0.0
		 */
		waitForEl(".meng-mcqs-basic-wrapper", (e) => {
			var response = {};
			var admin = new MengAdmin({ wrapper: ".meng-mcqs-basic-wrapper" });
			admin
				.request({
					action: "action_meng_mcqs_basic",
					postId: $("#ex_id").val(),
				})
				.then((res) => {
					response = res;
					console.log(response);
				});
			$(".mcqs-form").submit((e) => {
				e.preventDefault();
				var correctCounter = 0;
				var mcqs = $(".mcqs-form .meng-mcq-single");
				$.each(mcqs, (index, q) => {
					var qid = q.getAttribute("data-qid");
					var input = $(`input[name="mcq[${qid}]"]:checked`);
					var inputText = input
						.closest(".meng_radio")
						.find(".meng-mcq-option-name");
					if (
						input.val() &&
						response[qid].options.correct.toLowerCase().trim() ===
							input.val().toLowerCase().trim()
					) {
						correctCounter++;
						inputText.addClass("meng-correct-answer--text");
					} else {
						inputText.addClass("meng-wrong-answer--text");
					}
				});
				admin.showResult({
					dividend: correctCounter,
					divisor: Object.keys(response).length,
				});
			});
		});
		/**
		 * Quiz type: meng_mcqs_cloze
		 *
		 * @since 1.0.0
		 */
		waitForEl($(".meng-mcqs-cloze-wrapper"), function () {
			var response = {};
			var admin = new MengAdmin({ wrapper: ".meng-mcqs-cloze-wrapper" });
			admin
				.request({
					action: "action_meng_mcqs_cloze",
					postId: $(".meng-mcqs-cloze-wrapper").data("excercise"),
				})
				.then((res) => {
					response = res;
				});
			$(".meng-cloze-row").on("click", (e) => {
				var choiceRow = e.target.parentNode.getAttribute("data-option");
				if (
					response[choiceRow].options.correct === e.target.textContent.trim()
				) {
					e.target.classList.add("meng-correct-answer--text");
				} else {
					e.target.classList.add("meng-wrong-answer--text");
				}
			});
			admin.showAnswers({
				toggleTarget: ".meng_tabs_wrapper",
			});
		});
		// Tabs
		waitForEl($(".meng_tabs_wrapper"), () => {
			$(".meng_tabs_wrapper .meng_cloze_tab_content")[0].classList.add(
				"meng-active-tab-content"
			);
			$(".meng_tabs_headings .meng-tab-heading")[0].classList.add(
				"meng-active-tab"
			);
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
		});
		/**
		 * Quiz type: meng_blanks_basic
		 *
		 * @since 1.0.0
		 */
		waitForEl($(".meng-blanks-basic-wrapper"), (e) => {
			var response = {};
			var admin = new MengAdmin({ wrapper: ".meng-blanks-basic-wrapper" });
			admin
				.request({
					action: "action_meng_blanks_basic",
					postId: $("#ex_id").val(),
				})
				.then((res) => {
					response = res;
				});
			$(".meng-form").submit((e) => {
				e.preventDefault();
				var correctCounter = 0;
				$.each($("input.meng_blanks_basic_input"), (index, input) => {
					var qid = parseInt(input.getAttribute("data-qid"));
					if (
						response[qid].correct.toLowerCase().trim() ===
						input.value.toLowerCase().trim()
					) {
						correctCounter++;
						input.classList.remove("meng-wrong-answer");
						input.classList.add("meng-correct-answer");
					} else {
						input.classList.remove("meng-correct-answer");
						input.classList.add("meng-wrong-answer");
					}
				});
				admin.showResult({
					dividend: correctCounter,
					divisor: Object.keys(response).length,
				});
			});
		});
		/**
		 * Quiz type: meng_blanks_cols
		 *
		 * @since 1.0.0
		 */
		waitForEl(".meng-blanks-cols-wrapper", (e) => {
			var response = {};
			var admin = new MengAdmin({ wrapper: ".meng-blanks-cols-wrapper" });
			admin
				.request({
					action: "action_meng_blanks_cols",
					postId: parseInt($("input#ex_id").val()),
				})
				.then((res) => {
					response = res;
				});
			$("form.meng_blanks_cols_form").submit((e) => {
				e.preventDefault();
				var inputs = $("input.meng_blanks_cols_input");
				var correctCounter = 0;
				$.each(inputs, (i, v) => {
					var field_id = parseInt(v.getAttribute("data-field_id"));
					var option_id = parseInt(v.getAttribute("data-option_id"));
					if (
						response.fields[field_id].options_input[option_id] ===
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
				admin.showResult({
					dividend: correctCounter,
					divisor: inputs.length,
				});
			});
			admin.showAnswers();
		});
		/**
		 * Quiz type: meng_multi_selectors
		 *
		 * @since 1.0.0
		 */
		waitForEl(".meng-multi-selector-wrapper", (e) => {
			var response = {};
			var admin = new MengAdmin({ wrapper: ".meng-multi-selector-wrapper" });
			admin
				.request({
					action: "action_meng_multi_selector",
					postId: $(".meng-form #ex_id").val(),
				})
				.then((res) => {
					response = res;
				});
			var userCorrectAnswers = [];
			$(".meng-form input[type=checkbox]").on("click", (e) => {
				var qid = e.target.getAttribute("data-qid");
				var optionId = e.target.getAttribute("data-option_id");
				var label = e.target.parentNode;
				if (response) {
					if (response[qid].options.correct[optionId] === e.target.value) {
						label.classList.add("meng-msel-correct-answer");
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
					admin.showResult({
						dividend: userCorrectAnswers.length,
						divisor: totalCorrectAnswers,
					});
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
			var admin = new MengAdmin({ wrapper: ".meng-true-false-wrapper" });
			admin
				.request({
					action: "action_meng_true_false",
					postId: $("#ex_id").val(),
				})
				.then((res) => {
					response = res;
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
				admin.showResult({
					dividend: userCorrectAnswers.size,
					divisor: Object.keys(response).length,
				});
			});
		});
	});
})(jQuery);
