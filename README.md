# XRF Sync for Sandstorm

XRF Sync is a very light web service intended to allow instances of applications on different computers across the Internet to send each other commands. It is only partially implemented at this time, and largely operates as a heartbeat service for the various clients, though inter-client messaging is in progress. It was built to support the [HAController](https://github.com/ocdtrekkie/HAController) home automation software, however, you can monitor the connection of a computer using [SSCaaS](https://github.com/ocdtrekkie/SSCaaS) as well.

XRF Sync for Sandstorm operates XRF Sync via the HTTP API support in Sandstorm. As XRF Sync for Sandstorm evolves, it will fully embrace the Sandstorm app model, dropping support for multiple pools in exchange for multiple XRF Sync grains, and more heavily relying on Sandstorm's own authentication rather than independently generated keys. When XRF Sync for Sandstorm sufficiently diverges from compatibility with XRF Sync, the latter will be deprecated.

## Additional credits

Icons in this repository are modified from https://github.com/tabler/tabler-icons under the MIT License

Earth image courtesy of NASA