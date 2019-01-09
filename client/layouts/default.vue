<template>
	<div>
		<Alert />
		<Navigation />
		<nuxt />
	</div>
</template>

<script>
  import Navigation from '@/layouts/partials/Navigation';
  import Alert from '@/components/globals/Alert';

  export default {
	components: {
		Navigation,
		Alert
	}
  }

</script>
