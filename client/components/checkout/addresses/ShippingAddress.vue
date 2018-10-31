<template>
	<article class="message">
		<div class="message-body">
			<h1 class="title is-5">Ship to</h1>

			<template v-if="selectedAddress">
				{{ selectedAddress.name }}
			</template>

		</div>
	</article>
</template>

<script>
	export default {
		name: 'ShippingAddress',
		data () {
			return {
				localAddresses: this.addresses,
				selectedAddress: null
			};
		},
		props: {
			addresses: {
				required: true,
				type: Array
			}
		},
		computed: {
			defaultAddress () {
				return this.localAddresses.find((address) => {
					return address.default === true;
				});
			}
		},
		methods: {
			switchAddress (address) {
				this.selectedAddress = address
			}
		},
		created () {
			if (this.addresses.length) {
				this.selectedAddress(this.defaultAddress);
			}
		}
	};
</script>

<style scoped>

</style>