/**
 * WordPress dependencies.
 */
import { FormToggle } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';

/**
 * Main.
 */
const WPLDToggleScriptDebug = ( { checked, setState } ) => {
	return (
		<Fragment>
			<label
				htmlFor="enable-script-debug"
				className="components-toggle-control__label"
			>SCRIPT_DEBUG</label>
			<FormToggle
				id="enable-script-debug"
				checked={ checked }
				onChange={ () => setState( ( state ) => ( { checked: ! state.checked } ) ) }
			/>
		</Fragment>
	);
};

export default withState( ( checked, setState ) => {
	return (
		checked,
		setState
	);
} )( WPLDToggleScriptDebug );
