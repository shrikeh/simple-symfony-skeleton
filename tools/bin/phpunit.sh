#!/usr/bin/env bash

function phpunit()
{
    local BIN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
    local FILENAME="$( basename ${BASH_SOURCE[0]} )";

    cd "${BIN_DIR}/../../";

    command -v docker-compose >/dev/null 2>&1 || {
        echo "Docker compose not found, running phpunit in vagrant box";
        vagrant up;
        vagrant ssh -c "bash ./tools/bin/${FILENAME}";
        return 0;
    }

    docker-compose run phpunit;
}

phpunit;