<template>
<div class="grid grid-cols-12 gap-0">
	<div class="col-12">
		<div class="card">
			<h4>Text</h4>
			<p>Manage the alignment and the styling of a text with <a href="https://github.com/primefaces/primeflex">PrimeFlex</a> text utilities.</p>

			<h5>Alignment</h5>
			<div class="mb-4 text-left">Left</div>
			<div class="mb-4 text-center">Center</div>
			<div class="text-right">Right</div>

			<h5>Wrap</h5>
			<div class="mb-4 demo-container" style="width: 10rem">Long text wraps and does not overlow.</div>
			<div class="mb-4 demo-container whitespace-nowrap" style="width: 10rem">Long text does not wrap and overflows the parent.</div>

			<h5>Transform</h5>
			<div class="mb-4 lowercase">LOWERCASE</div>
			<div class="mb-4 uppercase">uppercase</div>
			<div class="text-capitalize">capitalize</div>

			<h5>Style</h5>
			<div class="mb-4 bold-bold">Bold</div>
			<div class="mb-4 bold-normal">Normal</div>
			<div class="mb-4 bold-light">Light</div>
			<div class="mb-4 bold-italic">Italic</div>
		</div>
	</div>
</div>
</template>

<script>
</script>

<style scoped>
	.demo-container {
		border: 1px solid var(--surface-d);
	}
</style>
