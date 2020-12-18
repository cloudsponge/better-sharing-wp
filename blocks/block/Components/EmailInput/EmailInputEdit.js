import React from 'react';

import Preview from './EmailInputSave';

const { __ } = wp.i18n;
const { Fragment } = wp.element;

const EmailInputEdit = (props) => {
  const { component } = props;

  return (
    <Fragment>{component === 'preview' && <Preview {...props} />}</Fragment>
  );
};

export default EmailInputEdit;
