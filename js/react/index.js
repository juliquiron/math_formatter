import _ from 'lodash'
import React from 'react';
import ReactDOM from 'react-dom';
import { ApolloProvider } from 'react-apollo';
import { ApolloClient } from 'apollo-client';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';

import Field from './components/Field';

// The connection object.
const client = new ApolloClient({
  link: new HttpLink({ uri: "/graphql" }),
  cache: new InMemoryCache()
});

// Consumes and renders the query result.
const MathField = (props) => (
  <ApolloProvider client={client}>
    <Field nid={props.nid} fieldName={props.fieldName} bundle={props.bundle} />
  </ApolloProvider>
);

var fields = document.getElementsByClassName('render--react-math-formatter');
Object.keys(fields).forEach(key => {
  // Reads field context from the template.
  let nid = fields[key].getAttribute('data-nid');
  let fieldName = _.camelCase(fields[key].getAttribute('data-field-name'));
  let bundleName = _.camelCase(fields[key].getAttribute('data-bundle'));
  let bundleQueryName = 'Node' + _.upperFirst(bundleName);
  ReactDOM.render(
    <MathField nid={nid} fieldName={fieldName} bundle={bundleQueryName} />,
    fields[key]
  );
});
