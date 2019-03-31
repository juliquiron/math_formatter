import gql from "graphql-tag";
import { Component } from 'react';

class FieldValue extends Component {

  constructor (props) {
    super(props);
  }

  state = {
    evaluated: false
  }

  updateExpression = (value) => {
    this.props.expression = this.props.expression + ' = ' + value.appendResult;
    this.setState({evaluated: true});
  }

  shouldComponentUpdate(nextProps, nextState) {
    return this.state.evaluated !== nextState.evaluated;
  }


  evaluate = (client) => {
    if (!this.state.evaluated) {
      client.query({
        query: gql`
          query calculate($expression: String!) {
            calculator(expression: $expression)
          }`,
        variables: {
          expression: this.props.expression
        }})
        .then(response => {
          this.updateExpression({
            appendResult: response.data.calculator
          });
        });
    }

  }

  // Unused since implemented GraphQL. Not removed for demonstrations proposes.
  evaluateREST = () => {
    if (!this.state.evaluated) {
      let requestURL = "/calculator/evaluate?_format=json&expression=" + this.props.expression;
      fetch(requestURL)
        .then(response => response.json())
        .then(data => {
          this.updateExpression({
            appendResult: data.value
          })
        });
    }
  };
  render () {
    return <div onMouseEnter={() => { this.evaluate(this.props.apolloClient) }}>{this.props.expression}</div>;
  }

}

export default FieldValue
