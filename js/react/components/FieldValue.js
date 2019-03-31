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

  evaluate = () => {
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
    return <div onMouseEnter={this.evaluate}>{this.props.expression}</div>;
  }

}

export default FieldValue
