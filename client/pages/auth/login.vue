<template>
	<div class="section">
		<div class="container is-fluid">
			<div class="columns is-centered">
				<div class="column is-6">
					<h1 class="title is-4">Sign in</h1>
					<form action="" @submit.prevent="login">
						<div class="field">
							<label class="label">Email</label>
							<div class="control">
								<input class="input" type="email" v-model="form.email">
							</div>
						</div>

						<div class="field">
							<label class="label">Password</label>
							<div class="control">
								<input class="input" type="password" v-model="form.password">
							</div>
						</div>

						<div class="field">
							<p class="control">
								<button class="button is-info is-medium">
									Sign in
								</button>
							</p>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'login',
		data () {
			return {
				form: {
					email: '',
					password: ''
				}
			}
		},
		middleware: [
			'redirectIfAuth'
		],
		methods: {
			async login () {
				await this.$auth.loginWith('local', {
					data: this.form
				})

				if (this.$route.query.redirect) {
					this.$router.replace(this.$route.query.redirect);
					return;
				}

				this.$router.replace({
					name: 'index'
				})
			}
		}
	}
</script>

<style scoped>

</style>