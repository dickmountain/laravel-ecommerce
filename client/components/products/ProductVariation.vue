<template>
	<div class="field">
		<label class="label">
			{{ type }}
		</label>
		<div class="controld">
			<div class="select is-fullwidth">
				<select name="" id="">
					<option value="">Please choose</option>
					<option
						v-for="variation in variations"
						:key="variation.id"
					>
						{{ variation.name }}
					</option>
				</select>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'ProductVariation',
		props: {
			type: {
				required:true,
				type: String
			},
			variations: {
				required:true,
				type: Array
			}
		}
	}
</script>

<style scoped>

</style>