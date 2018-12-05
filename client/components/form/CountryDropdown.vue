<template>
	<select @change="changed">
		<option value="">Select a country</option>
		<option :value="country.id" v-for="country in countries" :key="country.id">
			{{ country.name }}
		</option>
	</select>
</template>

<script>
	export default {
		name: 'CountryDropdown',
		data () {
			return {
				countries: []
			}
		},
		methods: {
			async getCountries () {
				let response = await this.$axios.$get('countries');

				this.countries = response.data;
			},
			changed ($event) {
				this.$emit('input', $event.target.value);
			}
		},
		created () {
			this.getCountries();
		}
	};
</script>

<style scoped>

</style>