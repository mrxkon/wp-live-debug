/**
 * WordPress dependencies.
 */
import { FormToggle } from '@wordpress/components';

/**
 * Main.
 */
const Toggle = ( { id, name, checked } ) => {
	return (
		<>
			<label
				htmlFor={ id }
				className="components-toggle-control__label"
			>{ name }</label>
			<FormToggle
				id={ id }
				checked={ checked }
				//onChange={ () => setState( ( state ) => ( { checked: ! state.checked } ) ) }
				//onClick={ console.log( 'eff' ) }
			/>
		</>
	);
};

export default Toggle;
