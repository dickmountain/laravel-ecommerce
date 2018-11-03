<template>
	<table class="table is-hoverable is-fullwidth">
		<tbody>
			<tr v-for="address in addresses" :key="address.key">
				<td>
					<p :class="{ 'has-text-weight-bold': address.id === selectedAddress.id }">
						{{ address.name }} <br>
					</p>
				</td>
				<td>
					<a href="" class="button is-info" @click.prevent="$emit('click', address)">Ship here</a>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
	export default {
		name: 'ShippingAddressSelector',
		props: {
			addresses: {
				required: true,
				type: Array
			},
			selectedAddress: {
				required: true,
				type: Object
			}
		}
	}
</script>

<style scoped>

</style>