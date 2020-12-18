import React from 'react';

import Inspector from './EmailFormInspector';
import Preview from './EmailFormSave';

const { __ } = wp.i18n;
const { Fragment, useEffect } = wp.element;
const { getPermalink } = wp.data.select('core/editor');

const EmailFormEdit = (props) => {
  const { attributes, setAttributes, component } = props;

  // when user selects a radio option
  const onControlChange = (option) => {
    switch (option) {
      case 'readonly':
        setAttributes({
          emailSubject: 'Sharing',
          emailMessage: `What a great way to save! Click ${getPermalink()}`,
          emailFormControl: 'readonly',
        });
        break;
      case 'hidden':
        setAttributes({
          emailSubject: 'Sharing',
          emailMessage: `What a great way to save! Click ${getPermalink()}`,
          emailFormControl: 'hidden',
        });
        break;
      case 'custom':
        setAttributes({
          emailSubject: 'Subject...',
          emailMessage: `Write a custom placeholder to give the user more info, or not! Up to you!`,
          emailFormControl: 'custom',
        });
        break;
      default:
        break;
    }
  };

  // first render w/ default value
  useEffect(() => {
    onControlChange(attributes.emailFormControl);
  }, []);

  return (
    <Fragment>
      {component === 'inspector' && (
        <Inspector {...props} onControlChange={onControlChange} />
      )}

      {component === 'preview' && <Preview {...props} />}
    </Fragment>
  );
};

export default EmailFormEdit;
