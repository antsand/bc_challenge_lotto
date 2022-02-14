<template>
	<div>
		<div>
			<div class="card">
				Enter 6 numbers (between 1 and 49) and we will check them against every 6/49 draw since 1981, and calculate your net winnings or losings.  
Note: A number cannot be greater than 49.
			</div>
			<div class="danger alert-danger alert" v-bind:class="{'hidden': !error }">
				<div v-html="error_string"></div>
				<div v-html="error_dup"></div>
				<div v-html="error_greater"></div>
				<div v-html="error_zero"></div>
				<div v-html="error_null"></div>
			</div>	
			<div class="input_container">
				<input type="text" v-for="i in lotto_num_enter" v-model="lotto_num[i-1]" class="input_num" @keyup="check_error(i-1)" v-bind:class="{'dup-error': dup.includes(i-1), 'greater-error': greater_49.includes(i-1), 'str-error': num_string.includes(i-1), 'zero-error': num_zero.includes(i-1)}">
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
			error_str:null,
			error_dup:null,
			error_greater:null,
			error_string:null,
			error_zero:null,
			error_null:null,
			results:null,	
			dup:[],
			greater_49:[],
			lotto_num_enter:6,
			num_string: [],
			num_zero:[],
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
		},
		num_string: {
			handler: function() {

			},
			deep: true,	
		},
		num_zero: {
			handler: function() {

			},
			deep: true,	
		},
		greater_49: {
			handler: function() {

			},
			deep: true,	
		}
	},
	methods: {
		check_error(i = null) {
			var error = false;	
			if (!(this.check_num_greater_49(i) || this.check_duplicates(i) || this.check_string(i) || this.check_zero(i))) {
				error = true;
			}
			/* if there are no dups at this index, check if other dups too are rectified 
			 * There will always be a pair of dups. If no pair, then clean it up.
			*/
			if (this.dup.length % 2) {
				var dup = this.check_all_dup();
				if (!dup) {
					this.error_dup = null;
				}
			}
			/*
			if (!error) {
				var str = this.check_all_strings();
				if (!str) {
					this.error_string = null;
				}
			}
			*/
			return error;
	
		},
		array_push(push_array, value) {
			/* do no add duplicate index to an array.
			 * Check before pushing.
			 */	
			if (!push_array.includes(value)) {
				push_array.push(value);
			}	
		},
		array_pop(pop_array, value) {
			var array_index = pop_array.indexOf(value);	
			if (array_index > -1) {
				pop_array.splice(array_index, 1);
			}
		},
		remove_error(error_array, string_obj) {
			if (!error_array.length) {
			     string_obj = null;
			}
		},
		check_string:function(index) {
			var error = false;
			if (isNaN(parseInt(this.lotto_num[index])) && (this.lotto_num[index])) {
				this.error_string = "Cannot enter strings";
				this.array_push(this.num_string, index);
				error = true;
				this.error = true;
			}
			if (!error) {
				this.array_pop(this.num_string, index);
				this.remove_error(this.num_string, this.error_string);
			}
			return error;
		},
		check_all_strings() {
			var i, error = 0;
			for (i=0; i<6; i++) {
			      var ret = this.check_string(i);
			      if (ret) {
				error = 1;	
			      }	
			}
			return error;
		},
		check_zero: function(index) {
			var error = false;	
			if (parseInt(this.lotto_num[index]) === 0) {
				this.error_zero = "Numbers  cannot be 0";
				this.array_push(this.num_zero, index);
				error = true;
				this.error = true;
			}
			if (!error) {
				this.array_pop(this.num_zero, index);
				this.remove_error(this.num_zero, this.error_zero);
			}
			return error;
		},
		check_num_greater_49:function(index) {
			var error = false;	
			if (this.lotto_num[index] > 49) {
				this.error_greater = "Numbers  cannot be more than 49";
				this.array_push(this.greater_49, index);
				error = true;
				this.error = true;
			}
			if (!error) {
				this.array_pop(this.greater_49, index);
				this.remove_error(this.greater_49, this.error_greater);
			}
			return error;
		},	
    		check_duplicates: function(index) {
			var error = false;
			var i;
			for (i = 0; i < this.lotto_num.length ; i++ ) {
				if (i == index) {
					continue;
				}	
				if (this.lotto_num[index] == this.lotto_num[i]) {
					this.error_dup = "There cannot be duplicate numbers";
					this.array_push(this.dup, i);
					this.array_push(this.dup, index);
					error = true;
					this.error = true;
				}
			}
			if (!error) {
				this.array_pop(this.dup, index);
				this.remove_error(this.dup, this.error_dup);
			}
			return error;
		},
		check_all_dup() {
			var i, error = 0;
			for (i=0; i<6; i++) {
			      var ret = this.check_duplicates(i);
			      if (ret) {
				error = 1;	
			      }	
			}
			return error;
		},
		check_null: function() {
			var i, error = false;
			this.error_null = null;	
			for (i=0; i<6; i++) {
				if (!this.lotto_num[i]) {
					this.error_null = "Cannot be null";
					error = true;
					this.error = true;
				}
			}	
			return error;	
		},
		check: function() {
		     if (this.check_null()) {
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
