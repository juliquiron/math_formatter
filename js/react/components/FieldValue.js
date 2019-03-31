import { Component } from 'react';

class FieldValue extends Component {

  constructor (props) {
    super(props);
  }

  state = {
    evaluated: false
  }
  shouldComponentUpdate (nextProps) {
    return this.props.value !== nextProps.value;
  }

  updateExpression = (value) => {
    this.props.value = this.props.value + ' = ' + value.appendResult;
    this.setState({evaluated: true});
  }

  shouldComponentUpdate(nextProps, nextState) {
    return this.state.evaluated !== nextState.evaluated;
  }

  evaluate = () => {
    if (!this.state.evaluated) {
      let requestURL = "/calculator/evaluate?_format=json&expression=" + this.props.value;
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
    return <div onMouseEnter={this.evaluate}>{this.props.value}</div>;
  }

}

export default FieldValue
