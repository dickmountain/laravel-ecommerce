<template>
	<div class="notification is-info alert" v-if="alert" @click.prevent="clear">
		{{ alert }}
	</div>
</template>

<script>
	import { mapGetters, mapActions } from 'vuex';

	export default {
		name: 'Alert',
		computed: {
			...mapGetters({
				alert: 'alert/message',
			})
		},
		methods: {
			...mapActions({
				clear: 'alert/clear',
			})
		}
	}
</script>

<style scoped>
	.alert{ border-radius: 0; }
</style>