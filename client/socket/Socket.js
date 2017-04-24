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
        this.host    = host;
        this.options = Object.assign({
            reconnect: true,
            interval: 1000
        }, options);

        this.connect();
    }

    /**
     * On socket connection
     */
    onConnection() {
        console.log('Connected');
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
        if ( this.options.reconnect ) {
            setTimeout(() => this.connect(), this.options.interval);
        }
    }

    /**
     * Bind the events
     * @private
     */
    _bind() {
        this.onopen    = this.onConnection;
        this.onmessage = this.onMessage;
        this.onclose   = this.onClose;
    }

    /**
     * Make the connection
     */
    connect() {
        this.socket = new WebSocket(this.host);
        this._bind();
    }
}