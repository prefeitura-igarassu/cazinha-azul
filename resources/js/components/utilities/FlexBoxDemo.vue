<template>
	<div class="grid grid-cols-12 gap-0">
		<div class="col-12 flexbox-demo">
			<div>
				<h4>FlexBox</h4>
				<p>Easily manage the layout of your components with the responsive FlexBox utilities. If you require a
					utility to manage the layout of your application,
					refer to the
					<router-link to="/grid">Grid System</router-link>
					instead.
				</p>

				<h5>Flex Container</h5>
				<p>An element can configured as a flexbox container using the <i>flex</i> or <i>inline-flex</i>
					classes.</p>

<pre v-code><code>
&lt;div class="flex"&gt;Flex Container&lt;/div&gt;
&lt;div class="inline-flex"&gt;Inline Flex Container&lt;/div&gt;

</code></pre>

				<div class="box flex mb-4">Flex Container</div>
				<div class="box inline-flex">Inline Flex Container</div>

				<h5>Direction</h5>
				<p>Default is applied using the <i>flex-{direction}</i> class where direction can be either of the
					following.</p>
				<ul>
					<li>row (default)</li>
					<li>row-reverse</li>
					<li>column</li>
					<li>column-reverse</li>
				</ul>

				<h6>Row</h6>
<pre v-code><code>
&lt;div class="flex"&gt;
	&lt;div class="mr-2"&gt;Item 1&lt;/div&gt;
	&lt;div class="mr-2"&gt;Item 2&lt;/div&gt;
	&lt;div&gt;Item 3&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex">
					<div class="mr-2">Item 1</div>
					<div class="mr-2">Item 2</div>
					<div>Item 3</div>
				</div>

				<h6>Column</h6>
<pre v-code><code>
&lt;div class="flex flex-col"&gt;
	&lt;div class="mb-2"&gt;Item 1&lt;/div&gt;
	&lt;div class="mb-2"&gt;Item 2&lt;/div&gt;
	&lt;div&gt;Item 3&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex flex-col">
					<div class="mb-2">Item 1</div>
					<div class="mb-2">Item 2</div>
					<div>Item 3</div>
				</div>

				<h6>Responsive</h6>
				<p>Row direction for larger screens and column for smaller.</p>

<pre v-code><code>
&lt;div class="flex flex-col md:flex-row"&gt;
	&lt;div class="mb-2 mr-2"&gt;Item 1&lt;/div&gt;
	&lt;div class="mb-2 mr-2"&gt;Item 2&lt;/div&gt;
	&lt;div class="mb-2 mr-2"&gt;Item 3&lt;/div&gt;
&lt;/div&gt;

</code></pre>

				<div class="flex flex-col md:flex-row">
					<div class="mb-2 mr-2">Item 1</div>
					<div class="mb-2 mr-2">Item 2</div>
					<div class="mb-2 mr-2">Item 3</div>
				</div>

				<h6>Direction Classes</h6>
				<ul>
					<li>flex-row</li>
					<li>flex-row-reverse</li>
					<li>flex-column</li>
					<li>flex-column-reverse</li>
					<li>sm:flex-row</li>
					<li>sm:flex-row-reverse</li>
					<li>sm:flex-column</li>
					<li>sm:flex-column-reverse</li>
					<li>md:flex-row</li>
					<li>md:flex-row-reverse</li>
					<li>md:flex-column</li>
					<li>md:flex-column-reverse</li>
					<li>lg:flex-row</li>
					<li>lg:flex-row-reverse</li>
					<li>lg:flex-column</li>
					<li>lg:flex-column-reverse</li>
					<li>xl:flex-row</li>
					<li>xl:flex-row-reverse</li>
					<li>xl:flex-column</li>
					<li>xl:flex-column-reverse</li>
				</ul>

				<h5>Order</h5>
				<p>Order configures the way in which they appear in the flex container. <i>flex-order-{value}</i> format is
					used where value can be a number from 0 to 6.</p>

				<h6>Customized</h6>
<pre v-code><code>
&lt;div class="flex"&gt;
	&lt;div class="mr-2 order-3"&gt;Item 1&lt;/div&gt;
	&lt;div class="mr-2 order-1"&gt;Item 2&lt;/div&gt;
	&lt;div class="mr-2 order-2"&gt;Item 3&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex">
					<div class="mr-2 order-3">Item 1</div>
					<div class="mr-2 order-1">Item 2</div>
					<div class="mr-2 order-2">Item 3</div>
				</div>

				<h6>Responsive</h6>
				<p>Orders change depending on the screen size.</p>
<pre v-code><code>
&lt;div class="flex"&gt;
	&lt;div class="mr-2 order-3 md:order-2"&gt;Item 1&lt;/div&gt;
	&lt;div class="mr-2 order-1 md:order-3"&gt;Item 2&lt;/div&gt;
	&lt;div class="mr-2 order-2 md:order-1"&gt;Item 3&lt;/div&gt;
&lt;/div&gt;

</code></pre>

				<div class="flex">
					<div class="mr-2 order-3 md:order-2">Item 1</div>
					<div class="mr-2 order-1 md:order-3">Item 2</div>
					<div class="mr-2 order-2 md:order-1">Item 3</div>
				</div>

				<h6>Order Classes</h6>
				<ul>
					<li>flex-order-0</li>
					<li>flex-order-1</li>
					<li>flex-order-2</li>
					<li>flex-order-3</li>
					<li>flex-order-4</li>
					<li>flex-order-5</li>
					<li>flex-order-6</li>
					<li>sm:flex-order-0</li>
					<li>sm:flex-order-1</li>
					<li>sm:flex-order-2</li>
					<li>sm:flex-order-3</li>
					<li>sm:flex-order-4</li>
					<li>sm:flex-order-5</li>
					<li>sm:flex-order-6</li>
					<li>md:flex-order-0</li>
					<li>md:flex-order-1</li>
					<li>md:flex-order-2</li>
					<li>md:flex-order-3</li>
					<li>md:flex-order-4</li>
					<li>md:flex-order-5</li>
					<li>md:flex-order-6</li>
					<li>lg:flex-order-0</li>
					<li>lg:flex-order-1</li>
					<li>lg:flex-order-2</li>
					<li>lg:flex-order-3</li>
					<li>lg:flex-order-4</li>
					<li>lg:flex-order-5</li>
					<li>lg:flex-order-6</li>
					<li>xl:flex-order-0</li>
					<li>xl:flex-order-1</li>
					<li>xl:flex-order-2</li>
					<li>xl:flex-order-3</li>
					<li>xl:flex-order-4</li>
					<li>xl:flex-order-5</li>
					<li>xl:flex-order-6</li>
				</ul>

				<h5>Wrap</h5>
				<p>Flex wrap defines the wrap behavior when there is not enough space in the container. The format of the
					class is <i>flex-{value}</i> and the value field can be either of the
					listed alternatives.</p>

				<ul>
					<li>nowrap (default)</li>
					<li>wrap</li>
					<li>wrap-reverse</li>
				</ul>

				<h6>No Wrap</h6>
<pre v-code><code>
&lt;div class="flex"&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 1&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 2&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 3&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 4&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 5&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 6&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex">
					<div class="mr-2 mb-2">Item 1</div>
					<div class="mr-2 mb-2">Item 2</div>
					<div class="mr-2 mb-2">Item 3</div>
					<div class="mr-2 mb-2">Item 4</div>
					<div class="mr-2 mb-2">Item 5</div>
					<div class="mr-2 mb-2">Item 6</div>
				</div>

				<h6>Wrap</h6>
<pre v-code><code>
&lt;div class="flex flex-wrap"&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 1&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 2&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 3&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 4&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 5&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 6&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex flex-wrap">
					<div class="mr-2 mb-2">Item 1</div>
					<div class="mr-2 mb-2">Item 2</div>
					<div class="mr-2 mb-2">Item 3</div>
					<div class="mr-2 mb-2">Item 4</div>
					<div class="mr-2 mb-2">Item 5</div>
					<div class="mr-2 mb-2">Item 6</div>
				</div>

				<h6>Wrap Reverse</h6>
<pre v-code><code>
&lt;div class="flex flex-wrap-reverse"&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 1&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 2&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 3&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 4&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 5&lt;/div&gt;
	&lt;div class="mr-2 mb-2"&gt;Item 6&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex flex-wrap-reverse">
					<div class="mr-2 mb-2">Item 1</div>
					<div class="mr-2 mb-2">Item 2</div>
					<div class="mr-2 mb-2">Item 3</div>
					<div class="mr-2 mb-2">Item 4</div>
					<div class="mr-2 mb-2">Item 5</div>
					<div class="mr-2 mb-2">Item 6</div>
				</div>

				<h6>Wrap Classes</h6>
				<ul>
					<li>flex-nowrap</li>
					<li>flex-wrap</li>
					<li>flex-wrap-reverse</li>
					<li>sm:flex-nowrap</li>
					<li>sm:flex-wrap</li>
					<li>sm:flex-wrap-reverse</li>
					<li>md:flex-nowrap</li>
					<li>md:flex-wrap</li>
					<li>md:flex-wrap-reverse</li>
					<li>lg:flex-nowrap</li>
					<li>lg:flex-wrap</li>
					<li>lg:flex-wrap-reverse</li>
					<li>xl:flex-nowrap</li>
					<li>xl:flex-wrap</li>
					<li>xl:flex-wrap-reverse</li>
				</ul>

				<h5>Justify Content</h5>
				<p>Justify content is the alignment along the main axis and defined using the <i>justify-content-{value}</i> format
					with responsive alternatives. Value field accepts
					the options below.</p>
				<ul>
					<li>start</li>
					<li>end</li>
					<li>center</li>
					<li>between</li>
					<li>around</li>
					<li>evenly</li>
				</ul>

				<h6>Between</h6>
<pre v-code><code>
&lt;div class="flex justify-between"&gt;
	&lt;div&gt;Item 1&lt;/div&gt;
	&lt;div&gt;Item 2&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex justify-between">
					<div>Item 1</div>
					<div>Item 2</div>
				</div>

				<h6>Center</h6>
<pre v-code><code>
&lt;div class="flex justify-center"&gt;
	&lt;div class="mr-2"&gt;Item 1&lt;/div&gt;
	&lt;div&gt;Item 2&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex justify-center">
					<div class="mr-2">Item 1</div>
					<div>Item 2</div>
				</div>

				<h6>Justify Content Classes</h6>
				<ul>
					<li>justify-content-start</li>
					<li>justify-content-end</li>
					<li>justify-content-center</li>
					<li>justify-content-between</li>
					<li>justify-content-around</li>
					<li>justify-content-evenly</li>
					<li>sm:justify-content-start</li>
					<li>sm:justify-content-end</li>
					<li>sm:justify-content-center</li>
					<li>sm:justify-content-between</li>
					<li>sm:justify-content-around</li>
					<li>sm:justify-content-evenly</li>
					<li>md:justify-content-start</li>
					<li>md:justify-content-end</li>
					<li>md:justify-content-center</li>
					<li>md:justify-content-between</li>
					<li>md:justify-content-around</li>
					<li>md:justify-content-evenly</li>
					<li>lg:justify-content-start</li>
					<li>lg:justify-content-end</li>
					<li>lg:justify-content-center</li>
					<li>lg:justify-content-between</li>
					<li>lg:justify-content-around</li>
					<li>lg:justify-content-evenly</li>
					<li>xl:justify-content-start</li>
					<li>xl:justify-content-end</li>
					<li>xl:justify-content-center</li>
					<li>xl:justify-content-between</li>
					<li>xl:justify-content-around</li>
					<li>xl:justify-content-evenly</li>
				</ul>

				<h5>Align Items</h5>
				<p>Align Items configuration is the alignment along the cross axis and defined using the <i>align-items-{value}</i>
					format with responsive alternatives. Value field accepts
					the options below.</p>
				<ul>
					<li>start</li>
					<li>end</li>
					<li>center</li>
					<li>baseline</li>
					<li>stretch (default)</li>
				</ul>

				<h6>Start</h6>
<pre v-code><code>
&lt;div class="flex items-center"&gt;
	&lt;div class="mr-2" style="height:100px"&gt;Item 1&lt;/div&gt;
	&lt;div style="height:50px"&gt;Item 2&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex items-center">
					<div class="mr-2" style="height:100px">Item 1</div>
					<div style="height:50px">Item 2</div>
				</div>

				<h6>Center</h6>
<pre v-code><code>
&lt;div class="flex items-center"&gt;
	&lt;div class="mr-2" style="height:100px"&gt;Item 1&lt;/div&gt;
	&lt;div style="height:50px"&gt;Item 2&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex items-center">
					<div class="mr-2" style="height:100px">Item 1</div>
					<div style="height:50px">Item 2</div>
				</div>

				<h6>Align Items Classes</h6>
				<ul>
					<li>align-items-start</li>
					<li>align-items-end</li>
					<li>align-items-center</li>
					<li>align-items-baseline</li>
					<li>align-items-strech</li>
					<li>sm:align-items-start</li>
					<li>sm:align-items-end</li>
					<li>sm:align-items-center</li>
					<li>sm:align-items-baseline</li>
					<li>sm:align-items-strech</li>
					<li>md:align-items-start</li>
					<li>md:align-items-end</li>
					<li>md:align-items-center</li>
					<li>md:align-items-baseline</li>
					<li>md:align-items-strech</li>
					<li>lg:align-items-start</li>
					<li>lg:align-items-end</li>
					<li>lg:align-items-center</li>
					<li>lg:align-items-baseline</li>
					<li>lg:align-items-strech</li>
					<li>xl:align-items-start</li>
					<li>xl:align-items-end</li>
					<li>xl:align-items-center</li>
					<li>xl:align-items-baseline</li>
					<li>xl:align-items-strech</li>
				</ul>

				<h5>Align Self</h5>
				<p>Align self configuration is the alignment along the cross axis for a particular element and defined using
					the <i>align-self-{value}</i> format with responsive alternatives. Value field accepts
					the options below.</p>
				<ul>
					<li>start</li>
					<li>end</li>
					<li>center</li>
					<li>baseline</li>
					<li>stretch (default)</li>
				</ul>

<pre v-code><code>
&lt;div class="flex" style="height: 150px;"&gt;
	&lt;div class="mr-2 self-start"&gt;Start&lt;/div&gt;
	&lt;div class="mr-2 self-center"&gt;Center&lt;/div&gt;
	&lt;div class="mr-2 self-end"&gt;End&lt;/div&gt;
	&lt;div class="mr-2 self-stretch"&gt;Stretch&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex" style="height: 150px;">
					<div class="mr-2 self-start">Start</div>
					<div class="mr-2 self-center">Center</div>
					<div class="mr-2 self-end">End</div>
					<div class="mr-2 self-stretch">Stretch</div>
				</div>

				<h6>Align Items Classes</h6>
				<ul>
					<li>align-self-start</li>
					<li>align-self-end</li>
					<li>align-self-center</li>
					<li>align-self-baseline</li>
					<li>align-self-strech</li>
					<li>sm:align-self-start</li>
					<li>sm:align-self-end</li>
					<li>sm:align-self-center</li>
					<li>sm:align-self-baseline</li>
					<li>sm:align-self-strech</li>
					<li>md:align-self-start</li>
					<li>md:align-self-end</li>
					<li>md:align-self-center</li>
					<li>md:align-self-baseline</li>
					<li>md:align-self-strech</li>
					<li>lg:align-self-start</li>
					<li>lg:align-self-end</li>
					<li>lg:align-self-center</li>
					<li>lg:align-self-baseline</li>
					<li>lg:align-self-strech</li>
					<li>xl:align-self-start</li>
					<li>xl:align-self-end</li>
					<li>xl:align-self-center</li>
					<li>xl:align-self-baseline</li>
					<li>xl:align-self-strech</li>
				</ul>

				<h5>Align Content</h5>
				<p>Align content is the alignment along the cross axis and defined using the <i>align-content-{value}</i> format with
					responsive alternatives. Value field accepts
					the options below.</p>
				<ul>
					<li>start</li>
					<li>end</li>
					<li>center</li>
					<li>around</li>
					<li>stretch</li>
					<li>between</li>
				</ul>

				<h6>Align Content Classes</h6>
				<ul>
					<li>align-content-start</li>
					<li>align-content-end</li>
					<li>align-content-center</li>
					<li>align-content-around</li>
					<li>align-content-stretch</li>
					<li>align-content-between</li>
					<li>sm:align-content-start</li>
					<li>sm:align-content-end</li>
					<li>sm:align-content-center</li>
					<li>sm:align-content-around</li>
					<li>sm:align-content-stretch</li>
					<li>sm:align-content-between</li>
					<li>md:align-content-start</li>
					<li>md:align-content-end</li>
					<li>md:align-content-center</li>
					<li>md:align-content-around</li>
					<li>md:align-content-stretch</li>
					<li>md:align-content-between</li>
					<li>lg:align-content-start</li>
					<li>lg:align-content-end</li>
					<li>lg:align-content-center</li>
					<li>lg:align-content-around</li>
					<li>lg:align-content-stretch</li>
					<li>lg:align-content-between</li>
					<li>xl:align-content-start</li>
					<li>xl:align-content-end</li>
					<li>xl:align-content-center</li>
					<li>xl:align-content-around</li>
					<li>xl:align-content-stretch</li>
					<li>xl:align-content-between</li>
				</ul>

				<h5>Margin with FlexBox</h5>
				<p>When combined with
					<router-link to="/spacing">spacing utilities</router-link>
					, flexbox offers endless possibilities.
				</p>
				<h6>Horizontal Spacing</h6>

<pre v-code><code>
&lt;div class="flex p-4 card"&gt;
	&lt;Button type="Button" icon="pi pi-check" class="mr-2" /&gt;
	&lt;Button type="Button" icon="pi pi-trash" class="p-button-danger"/&gt;
	&lt;Button type="Button" icon="pi pi-search" class="ml-auto p-button-help"/&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex p-4 card">
					<Button type="Button" icon="pi pi-check" class="mr-2"/>
					<Button type="Button" icon="pi pi-trash" class="p-button-danger"/>
					<Button type="Button" icon="pi pi-search" class="ml-auto p-button-help"/>
				</div>

				<h6>Vertical Spacing</h6>
<pre v-code><code>
&lt;div class="flex flex-col" style="height:150px"&gt;
	&lt;div&gt;Item 1&lt;/div&gt;
	&lt;div class="mt-auto"&gt;Item 2&lt;/div&gt;
&lt;/div&gt;

</code></pre>
				<div class="flex flex-col" style="height:150px">
					<div>Item 1</div>
					<div class="mt-auto">Item 2</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
</script>

<style lang="scss" scoped>
::v-deep(.flexbox-demo){
	pre[class*="language-"] {
		box-shadow: none;
		border: 0 none;
		background: none !important;

		&:before, &:after {
			display: none !important;
		}

		code {
			border-left: 10px solid var(--surface-d) !important;
			box-shadow: none !important;
			background: var(--surface-e) !important;
			margin: 1em 0;
			color: var(--text-color);
			font-size: 14px;

			.token {
				&.tag,
				&.keyword {
					color: #2196F3 !important;
				}

				&.attr-name,
				&.attr-string {
					color: #2196F3 !important;
				}

				&.attr-value {
					color: #4CAF50 !important;
				}

				&.punctuation {
					color: var(--text-color);
				}

				&.operator,
				&.string {
					background: transparent;
				}
			}
		}
	}
   .flex > div,
   .box {
		background-color: var(--surface-e);
		text-align: center;
		padding: 1rem;
		border-radius: 4px;
		box-shadow: 0 2px 1px -1px rgba(0,0,0,.2), 0 1px 1px 0 rgba(0,0,0,.14), 0 1px 3px 0 rgba(0,0,0,.12);
   }
}

</style>
