<template>
	<tr>
		<td>
			#{{ order.id }}
		</td>
		<td>
			{{ order.created_at }}
		</td>
		<td>
			<div v-for="product in products" :key="product.id">

				<a href="">Product 1</a>
			</div>
			<template v-if="moreProducsCount > 0">
				and {{ moreProducsCount }} more
			</template>
		</td>
		<td>{{ order.subtotal }}</td>
		<td>
            <span class="tag is-medium" :class="statusClasses">
              {{ order.status }}
            </span>
		</td>
	</tr>
</template>

<script>
	export default {
		name: 'Order',
		data () {
			return {
				maxProducts: 2,
				statusClasses: {
					'is-success': this.order.status === 'complete',
					'is-info': this.order.status === 'processing' || this.order.status === 'pending',
					'is-danger': this.order.status === 'payment_failed',
				}
			};
		},
		props: {
			order: {
				required: true,
				type: Object
			}
		},
		computed: {
			products () {
				return this.order.products.slice(0, this.maxProducts);
			},
			moreProducsCount () {
				return this.order.products.length - this.maxProducts;
			}

		}
	};
</script>

<style scoped>

</style>