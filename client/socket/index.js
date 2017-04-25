import Socket from './Socket'
import { SOCKET_SERVER } from '../constants'

const identity = localStorage.getItem('identity') ? `?identity=${localStorage.getItem('identity')}` : '';

export const socket = new Socket(`${SOCKET_SERVER}${identity}`);