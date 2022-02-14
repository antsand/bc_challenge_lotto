<template>
	<div>
		<div>
			<div class="card">
				Enter 6 numbers (between 1 and 49) and we will check them against every 6/49 draw since 1981, and calculate your net winnings or losings.  
Note: A number cannot be greater than 49.
			</div>
			<div v-html="error" class="danger alert-danger alert" v-bind:class="{'hidden': !error }">
			</div>	
			<div class="input_container">
				<input type="text" v-for="i in lotto_num_enter" v-model="lotto_num[i-1]" class="input_num" @keyup="check_error()" v-bind:class="{'dup-error': dup.includes(i-1), 'greater-error': greater_49.includes(i-1), 'no-error': !(dup.includes(i-1) | greater_49.includes(i-1))}">
			</div>
			<br>
			<button class="btn btn-primary" @click="check">Check the Numbers</button>
		</div>
		<br>
		<div v-if="results" class="results">
			<div class="" v-if="results.lotto_win">
				<h4>Times you won equal to or more than $85 </h4>
				<div v-for="result in results.lotto_win">
					<p>
						Lottery # <span v-html="result.id"></span>
						Date: <span v-html="result.year"></span>
						Got <span v-html="result.match"></span> matches right.  
						Won: <span v-html="result.won"></span>
					</p>
				</div>
			</div>
			<h4>Your oveall performance</h4>
			<div class="" v-if="results.overall">
				<div> 
					Total Earnings: ${{ results.overall.total_earnings }} <br>
					Total Spent: ${{ results.overall.total_spent }} <br>
					Win/Loss: ${{ results.overall.total_earnings - results.overall.total_spent }} <br>
					
				</div>
			</div>
		</div>
	</div>	
</template>
<script>
//import table_view from './table.vue'
//import create_boat from './create.vue'

export default {
	data() {
		return {
			data: null,
			lotto_num:[],
			duplicates: null,
			error: null,
			results:null,	
			dup:[],
			greater_49:[],
			lotto_num_enter:6
		}
	},
	components: {
//		'boat-table': table_view,
//		'create-boat': create_boat
	},
	watch: {
		lotto_num: {
			handler: function() {

			},
			deep: true,	
		},
		result: {
			handler: function() {

			},
			deep: true,	
		},
		dup: {
			handler: function() {

			},
			deep: true,	
		}
	},
	methods: {
		check_error() {
			this.check_num_greater_49();
			this.check_duplicates();
		},
		check_num_greater_49:function() {
			var index;
			var max = false;	
			this.greater_49 = [];
			for (index = 0; index < 6; index++ ) {
				if (this.lotto_num[index] > 49) {
					this.error = "Numbers  cannot be more than 49";
					this.greater_49.push(index);
					max = true;
					return true;
				}
			} 
			if (!max) {
				this.error = null;
				return false;
			}
		},	
    		check_duplicates: function() {
			var index, i;
			this.dup = [];
			for (index = 0; index < this.lotto_num.length - 1; index++ ) {	
				for (i = index+1; i < this.lotto_num.length ; i++ ) {	
					if (this.lotto_num[index] == this.lotto_num[i]) {
						this.error = "There cannot be duplicate numbers";
						this.duplciates = true;
						this.dup.push(i);
						this.dup.push(index);
						return true;
					}
				}
			}
		},
		check_null: function() {
			var index;
			for (index = 0; index < 6; index++ ) {
				if (!this.lotto_num[index]) {
					this.error = "Cannot be null";
					return true;
				}
			}	
		},
		check: function() {
		     if (this.check_null() || this.check_duplicates() || this.check_num_greater_49()) {
			        this.results = null;
				return;
		     } 
		     this.error = null;		
		     var formData = new FormData();
			formData.append('lotto_numbers', JSON.stringify(this.lotto_num));
		     this.$http.post('/lotto/get', formData)
                        .then(response => {                         
                          console.log(response.bodyText);
			  if (response.bodyText) {	 
			      this.results = JSON.parse(response.bodyText);
			  }	 
                         }, error => { 
                              console.log(error);                                
			}               
                     );
		}
	},
	watch: {
		data: {
			handler: function(newVal, oldVal) {
			},
			deep:true,
		}
	},
	mounted: function() {
	}
}

</script>
<style lang="scss">
</style>
