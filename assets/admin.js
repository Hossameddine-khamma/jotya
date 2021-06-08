/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
const inputs = ["Styles","Saisons","Types","Tailles","Budgets"]

inputs.forEach(input => {
  var styles = document.getElementById(input);
  var output = document.getElementById(`${input}value`);
  var output1=  document.getElementById(`${input}value1`);

  output.href = `/admin/${input}/edit/${styles.value}`;
  output1.href = `/admin/${input}/del/${styles.value}`;


  styles.oninput = function() {
    output.href = `/admin/${input}/edit/${this.value}`;
    output1.href = `/admin/${input}/del/${this.value}`;
  }
});

