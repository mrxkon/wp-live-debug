/**
 * WordPress dependencies.
 */
import { Fragment, Component } from '@wordpress/element';
import { FormToggle } from '@wordpress/components';

/**
 * Main.
 */
class Toggle extends Component {
	render() {
		return (
			<Fragment>
				<label
					htmlFor={ this.props.id }
					className="components-toggle-control__label"
				>{ this.props.name }</label>
				<FormToggle
					id={ this.props.id }
					checked={ this.props.checked }
					//onChange={ () => setState( ( state ) => ( { checked: ! state.checked } ) ) }
					//onClick={ console.log( 'eff' ) }
				/>
			</Fragment>
		);
	}
}

export default Toggle;
