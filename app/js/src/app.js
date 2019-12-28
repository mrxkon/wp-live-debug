/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * Internal Dependencies.
 */
import Header from './components/Header';
import Content from './components/Content';

/**
 * Main.
 */
class App extends Component {
	render() {
		return (
			<>
				<Header />
				<Content />
			</>
		);
	}
}

export default App;
