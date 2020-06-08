<?php

if( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="meng-admin-tab-content--help">
	<div class="meng-admin-section meng-mcqs-basic">
		<h2>1. MCQs Basic</h2>
		<div class="meng-admin-row">
			<h4>Shortcode</h4>
			<span><pre>[meng_mcqs_basic]</pre></span>
		</div>
		<div class="meng-admin-row">
			<h4>Attributes</h4>
			<table>
				<tbody>
					<tr>
						<th>Attribute Name</th>
						<th>Description</th>
						<th>Example usage</th>
					</tr>
					<tr>
						<td><pre>id</pre></td>
						<td><b>Required</b><p>Id of the post/excercise of MCSs Basic</p></td>
						<td><pre>[meng_mcqs_basic id="1"]</pre></td>
					</tr>
					<tr>
						<td><pre>layout</pre></td>
						<td>
							<b>Optional</b>
							<p>Layout of the mcqs. Possible options are: <pre>simple, infography</pre> If infography is selected, the excercise content should be set and the infography picture should be set as featured image.</p>
						</td>
						<td>
							<pre>[meng_mcqs_basic id="1" layout="simple"]</pre>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="meng-admin-section meng-mcqs-cloze">
		<h2>2. MCQs Cloze</h2>
		<div class="meng-admin-row">
			<h4>Shortcode</h4>
			<span><pre>[meng_mcqs_cloze]</pre></span>
		</div>
		<div class="meng-admin-row">
			<h4>Attributes</h4>
			<table>
				<tbody>
					<tr>
						<th>Attribute Name</th>
						<th>Description</th>
						<th>Example usage</th>
					</tr>
					<tr>
						<td><pre>id</pre></td>
						<td><b>Required</b><p>Id of the post/excercise of MCSs Cloze</p></td>
						<td><pre>[meng_mcqs_cloze id="1"]</pre></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="meng-admin-section meng-sortables-basic">
		<h2>3. Sortables Basic</h2>
		<div class="meng-admin-row">
			<h4>Shortcode</h4>
			<span><pre>[meng_sortables_basic]</pre></span>
		</div>
		<div class="meng-admin-row">
			<h4>Attributes</h4>
			<table>
				<tbody>
					<tr>
						<th>Attribute Name</th>
						<th>Description</th>
						<th>Example usage</th>
					</tr>
					<tr>
						<td><pre>id</pre></td>
						<td><b>Required</b><p>Id of the post/excercise of Sortables Basic</p></td>
						<td><pre>[meng_sortables_basic id="1"]</pre></td>
					</tr>
					<tr>
						<td><pre>layout</pre></td>
						<td>
							<b>Optional</b>
							<p>Layout of the sortables. Possible options are: <pre>simple, left</pre> Simple would show two equal columns. Left would show left column 25% and right one as 75%</p>
						</td>
						<td>
							<pre>[meng_sortables_basic id="1" layout="simple"]</pre>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</div>