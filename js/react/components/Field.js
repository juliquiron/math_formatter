import gql from "graphql-tag";
import { Component } from 'react';
import { Query } from 'react-apollo';

import FieldValue from './FieldValue'

// The formatter can render any configured field name for any bundle. This
// GraphQL query uses placeholders to fetch the proper field and use the
// proper bundle fragment. Use string replace as a workaround it.
// Note: React formatter only supports plain text fields, so is not
//       need to deal with properties in the field value.
const baseQuery = `query fieldValues($nodeId: String!) {
  nodeById(id: $nodeId) {
    ... on bundlePlaceholder {
      fieldPlaceholder
    }
  }
}`

const Field = (props) => (
  <Query
  query={
    gql(baseQuery
      .replace('fieldPlaceholder', props.fieldName)
      .replace('bundlePlaceholder', props.bundle)
    )
  }
  variables={{
    nodeId: props.nid
  }}>

  {({ loading, error, data }) => {
    if (loading) return "Loading...";
    if (error) return `Error: ${error.message}`;

    return data.nodeById.fieldEval.map(( expression ) => (
      <FieldValue expression={expression}/>
    ));
  }}

  </Query>
);

export default Field;
