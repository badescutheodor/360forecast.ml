import _ from 'lodash'

/**
 * Simple WebSocket wrapper
 */
export default class Socket {
    /**
     * The class constructor
     * @param host
     * @param options
     */
    constructor(host, options) {
        this.events  = {};
        this.host    = host;
        this.options = Object.assign({
            reconnect: true,
            interval: 1000
        }, options);
        this.connect();
    }

    /**
     * Reconnects to the socket server
     */
    reconnect() {
        if ( this.socket )
        {
            delete this.socket;
        }

        this.connect();
    }

    /**
     * On socket connection
     */
    onConnection() {
        _.has(this.events, 'connection')  && _.each(this.events.connection, (callback) => { callback() });
    }

    /**
     * On socket message
     * @param message
     */
    onMessage(message) {
        try
        {
            let data    = JSON.parse(message.data);
            let event   = data[0];
            let payload = data[1];

            if ( _.has(this.events, event) )
            {
                _.each(this.events[event], (callback) => { callback(payload); })
            }
        }
        catch(error)
        {
            console.error(error);
        }
    }

    /**
     * On connection close
     */
    onClose() {
        this.options.reconnect && setTimeout(() => this.connect, this.options.interval);
    }

    /**
     * Emit an event to server
     *
     * @param event
     * @param payload
     */
    emit(event, payload) {
        this.socket.send(JSON.stringify([event, payload]));
    }

    /**
     * Register an event to events object
     * @param event
     * @param callback
     */
    on(event, callback) {
        if ( !_.has(this.events, event) )
        {
            this.events[event] = [callback];
            return;
        }

        this.events[event].push(callback);
    }

    /**
     * Bind the events
     * @private
     */
    _bind() {
        this.socket.onopen    = this.onConnection.bind(this);
        this.socket.onmessage = this.onMessage.bind(this);
        this.socket.onclose   = this.onClose.bind(this);
    }

    /**
     * Make the connection
     */
    connect() {
        this.socket = new WebSocket(this.host);
        this._bind();
    }
}